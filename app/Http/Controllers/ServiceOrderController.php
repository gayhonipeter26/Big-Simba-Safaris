<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceOrder;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ServiceOrderController extends Controller
{
    public function __construct(protected MpesaService $mpesaService) {}

    public function index()
    {
        return Inertia::render('Service/Index', [
            'services' => Service::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'message' => 'nullable|string',
        ]);

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        $validated['payment_status'] = 'pending';
        $validated['status'] = 'pending';

        $service = Service::find($validated['service_id']);
        $validated['paid_amount'] = $service ? $service->starting_price : 0;

        if (($request->payment_method ?? 'mpesa') === 'mpesa') {
            $mpesaResponse = $this->mpesaService->stkPush(
                $validated['phone'],
                $validated['paid_amount'],
                strtoupper(uniqid('SIMBA-SRV-')),
                'Service: '.($service->name ?? 'Induction')
            );

            if (($mpesaResponse['ResponseCode'] ?? '') === '0') {
                $validated['payment_reference'] = $mpesaResponse['MerchantRequestID'];
                $validated['payment_method'] = 'mpesa';
                ServiceOrder::create($validated);

                return back()->with('success', 'Tactical authorization requested. Check your mobile device for M-Pesa induction prompt.');
            }

            throw ValidationException::withMessages([
                'payment_method' => 'M-Pesa API rejected the prompt. Check your phone number and try again.',
            ]);
        }

        $validated['payment_reference'] = strtoupper(uniqid('SIMBA-SRV-VISA-'));
        $validated['payment_method'] = 'visa';
        ServiceOrder::create($validated);

        return redirect()->back()->with('success', 'Support authorized via Credit. Mission briefing initiated.');
    }
}
