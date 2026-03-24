<?php

namespace App\Http\Controllers;

use App\Models\Fleet;
use App\Models\FleetHire;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FleetHireController extends Controller
{
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
        ]);

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        FleetHire::create($validated);

        return redirect()->back()->with('success', 'Your fleet deployment request has been transmitted. Our logistics team will contact you shortly.');
    }
}
