<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FleetHire;
use App\Models\ServiceOrder;
use App\Models\TourEnquiry;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    public function __construct(protected MpesaService $mpesaService) {}

    /**
     * Initiate STK Push (B2C) mission protocol
     */
    public function initiateStkPush(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|numeric',
            'reference' => 'required|string',
            'order_id' => 'required|integer',
            'order_type' => 'required|string|in:booking,fleet,service,enquiry',
            'description' => 'nullable|string',
        ]);

        $response = $this->mpesaService->stkPush(
            $validated['phone'],
            $validated['amount'],
            $validated['reference'],
            $validated['description'] ?? 'Payment for Safari Services'
        );

        if (isset($response['MerchantRequestID'])) {
            $this->saveMerchantRequestId(
                $validated['order_id'],
                $validated['order_type'],
                $response['MerchantRequestID']
            );
        }

        return response()->json($response);
    }

    /**
     * Handle STK Push Result (M-Pesa Callback) mission protocol
     */
    public function callback(Request $request)
    {
        $payload = $request->all();
        Log::channel('single')->info('M-Pesa STK Callback Received:', $payload);

        $resultCode = $payload['Body']['stkCallback']['ResultCode'] ?? null;
        $merchantRequestId = $payload['Body']['stkCallback']['MerchantRequestID'] ?? null;

        if ($resultCode == 0) {
            $metadata = $payload['Body']['stkCallback']['CallbackMetadata']['Item'] ?? [];
            $amount = null;
            $mpesaReceiptNumber = null;

            foreach ($metadata as $item) {
                if ($item['Name'] === 'Amount') {
                    $amount = $item['Value'];
                }
                if ($item['Name'] === 'MpesaReceiptNumber') {
                    $mpesaReceiptNumber = $item['Value'];
                }
            }

            $this->updatePaymentStatus($merchantRequestId, $mpesaReceiptNumber, $amount);
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    /**
     * C2B Validation Endpoint mission protocol
     */
    public function c2bValidation(Request $request)
    {
        Log::info('M-Pesa C2B Validation Request:', $request->all());

        // Return Success to Safaricom (Validation passed)
        // You can add logic here to reject specific payments if needed
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted',
        ]);
    }

    /**
     * C2B Confirmation Endpoint mission protocol
     */
    public function c2bConfirmation(Request $request)
    {
        $payload = $request->all();
        Log::info('M-Pesa C2B Confirmation Payload:', $payload);

        $receipt = $payload['TransID'] ?? null;
        $amount = $payload['TransAmount'] ?? null;
        $reference = $payload['BillRefNumber'] ?? null; // This is what the user typed as Account Number

        // If reference matches a record, update it
        $this->updatePaymentByManualReference($reference, $receipt, $amount);

        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted',
        ]);
    }

    /**
     * Register C2B URLs mission command
     */
    public function registerUrls(Request $request)
    {
        $validated = $request->validate([
            'shortcode' => 'required|string',
        ]);

        $appUrl = config('app.url');
        $validationUrl = "{$appUrl}/api/mpesa/c2b/validation";
        $confirmationUrl = "{$appUrl}/api/mpesa/c2b/confirmation";

        $response = $this->mpesaService->registerUrls(
            $validated['shortcode'],
            $validationUrl,
            $confirmationUrl
        );

        return response()->json($response);
    }

    /**
     * Tactical Mock Success for Local Testing mission protocol
     */
    public function mockSuccess(string $merchantRequestId)
    {
        if (config('app.env') !== 'local') {
            return response()->json(['error' => 'Mission restricted to local HQ.'], 403);
        }

        $receipt = 'MOCK'.strtoupper(bin2hex(random_bytes(4)));
        $amount = 100.00;

        $this->updatePaymentStatus($merchantRequestId, $receipt, $amount);

        return response()->json([
            'status' => 'Mock Success Protocol Initiated',
            'receipt' => $receipt,
            'merchant_id' => $merchantRequestId,
        ]);
    }

    private function saveMerchantRequestId($orderId, $orderType, $merchantRequestId)
    {
        $model = match ($orderType) {
            'booking' => Booking::find($orderId),
            'fleet' => FleetHire::find($orderId),
            'service' => ServiceOrder::find($orderId),
            'enquiry' => TourEnquiry::find($orderId),
            default => null
        };

        if ($model) {
            $model->update([
                'payment_reference' => $merchantRequestId,
                'payment_method' => 'mpesa',
            ]);
        }
    }

    private function updatePaymentStatus($merchantRequestId, $receipt, $amount)
    {
        $payload = [
            'payment_status' => 'paid',
            'status' => 'approved',
            'payment_id' => $receipt,
            'paid_amount' => $amount,
        ];

        if ($booking = Booking::where('payment_reference', $merchantRequestId)->first()) {
            $booking->update($payload);
        } elseif ($fleet = FleetHire::where('payment_reference', $merchantRequestId)->first()) {
            $fleet->update($payload);
        } elseif ($service = ServiceOrder::where('payment_reference', $merchantRequestId)->first()) {
            $service->update($payload);
        } elseif ($enquiry = TourEnquiry::where('payment_reference', $merchantRequestId)->first()) {
            $enquiry->update($payload);
        }
    }

    private function updatePaymentByManualReference($reference, $receipt, $amount)
    {
        $payload = [
            'payment_status' => 'paid',
            'status' => 'approved',
            'payment_id' => $receipt,
            'paid_amount' => $amount,
            'payment_method' => 'mpesa_c2b',
        ];

        // Try to find by custom reference (e.g. Booking Code)
        if ($booking = Booking::where('booking_confirmation_code', $reference)->first()) {
            $booking->update($payload);
        }
    }
}
