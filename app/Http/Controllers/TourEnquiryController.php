<?php

namespace App\Http\Controllers;

use App\Models\Fleet;
use App\Models\Tour;
use App\Models\TourEnquiry;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TourEnquiryController extends Controller
{
    public function __construct(protected MpesaService $mpesaService) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'fleet_id' => 'nullable|exists:fleets,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'guests' => 'required|integer|min:1|max:100',
            'message' => 'nullable|string',
            'payment_method' => 'required|string|in:mpesa,visa',
        ]);

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        $validated['payment_status'] = 'pending';
        $validated['status'] = 'pending';
        $tour = Tour::find($validated['tour_id']);
        $baseTourDeposit = $tour ? round($tour->price * 0.1) : 5000.00; // 10% tactical deposit

        $fleetDeposit = 0;
        if (! empty($validated['fleet_id'])) {
            $fleet = Fleet::find($validated['fleet_id']);
            $pricingModel = $request->input('fleet_pricing_model');
            if ($fleet && $pricingModel) {
                if ($pricingModel === 'hourly') {
                    $fleetDeposit = $fleet->price_per_hour;
                }
                if ($pricingModel === 'daily') {
                    $fleetDeposit = $fleet->price_per_day;
                }
                if ($pricingModel === 'safari') {
                    $fleetDeposit = $fleet->price_per_safari_drive;
                }
            }
        }

        $validated['paid_amount'] = $baseTourDeposit + $fleetDeposit;

        if ($validated['payment_method'] === 'mpesa') {
            $mpesaResponse = $this->mpesaService->stkPush(
                $validated['phone'],
                $validated['paid_amount'],
                strtoupper(uniqid('SIMBA-EXP-')),
                "Expedition: {$validated['name']}"
            );

            if (($mpesaResponse['ResponseCode'] ?? '') === '0') {
                $validated['payment_reference'] = $mpesaResponse['MerchantRequestID'];
                TourEnquiry::create($validated);

                return back()->with('success', 'M-Pesa authorization prompt dispatched. Expedition status will update immediately upon confirmed transmission.');
            }

            throw ValidationException::withMessages([
                'payment_method' => 'M-Pesa API rejected the prompt. Check your phone number and try again.',
            ]);
        }

        // Mock Visa handling
        $validated['payment_reference'] = strtoupper(uniqid('SIMBA-EXP-VISA-'));
        TourEnquiry::create($validated);

        if (auth()->check()) {
            return redirect()->route('dashboard')->with('success', 'Expedition secured via credit. Mission parameters awaiting final tactical review.');
        }

        return redirect()->route('home')->with('success', 'Expedition secured via credit. Mission parameters awaiting final tactical review.');
    }

    public function destroy(Request $request, TourEnquiry $tourEnquiry)
    {
        if ($tourEnquiry->user_id !== auth()->id()) {
            abort(403, 'Unauthorized tactical override attempt.');
        }

        $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);

        if ($tourEnquiry->created_at->diffInHours(now()) > 24) {
            return back()->with('error', 'TACTICAL DELAY: Request cancellation threshold exceeded (24h). Mission abort requires Admin Command override.');
        }

        if ($tourEnquiry->status !== 'pending') {
            return back()->with('error', 'MISSION LOCKED: Non-pending requests require direct command override.');
        }

        $tourEnquiry->update(['cancellation_reason' => $request->reason]);
        $tourEnquiry->delete();

        return back()->with('success', 'MISSION ABORTED: Expedition request successfully expunged from primary registry.');
    }
}
