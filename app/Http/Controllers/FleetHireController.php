<?php

namespace App\Http\Controllers;

use App\Models\Fleet;
use App\Models\FleetHire;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FleetHireController extends Controller
{
    public function __construct(protected MpesaService $mpesaService) {}

    public function index()
    {
        return Inertia::render('Fleet/Index', [
            'fleet' => Fleet::where('is_available', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fleet_id' => 'required|exists:fleets,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'message' => 'nullable|string',
            'payment_method' => 'required|string|in:mpesa,visa',
            'pricing_model' => 'required|string|in:hourly,daily,safari',
        ]);

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        $validated['payment_status'] = 'pending';
        $validated['status'] = 'pending';
        $fleet = Fleet::find($validated['fleet_id']);
        $pricingModel = $request->input('pricing_model');
        $amount = 5000; // default safety base

        if ($fleet) {
            if ($pricingModel === 'hourly') {
                $amount = $fleet->price_per_hour;
            }
            if ($pricingModel === 'daily') {
                $amount = $fleet->price_per_day;
            }
            if ($pricingModel === 'safari') {
                $amount = $fleet->price_per_safari_drive;
            }
        }

        $validated['paid_amount'] = (float) $amount;

        if ($validated['payment_method'] === 'mpesa') {
            $mpesaResponse = $this->mpesaService->stkPush(
                $validated['phone'],
                $validated['paid_amount'],
                strtoupper(uniqid('SIMBA-FLT-')),
                "Fleet Hire: {$validated['name']}"
            );

            if (($mpesaResponse['ResponseCode'] ?? '') === '0') {
                $validated['payment_reference'] = $mpesaResponse['MerchantRequestID'];
                FleetHire::create($validated);

                return back()->with('success', 'M-Pesa STK Prompt sent to your device. Mission will be authorized upon successful verification.');
            }

            throw ValidationException::withMessages([
                'payment_method' => 'M-Pesa API rejected the prompt. Check your phone number and try again.',
            ]);
        }

        // Handle Visa or other methods (mocked for now)
        $validated['payment_reference'] = strtoupper(uniqid('SIMBA-VISA-'));
        $fleetHire = FleetHire::create($validated);

        if (auth()->check()) {
            return redirect()->route('dashboard')->with('success', 'Credit authorized. Deployment pending final operational review.');
        }

        return redirect()->route('home')->with('success', 'Credit authorized. Deployment pending final operational review.');
    }
}
