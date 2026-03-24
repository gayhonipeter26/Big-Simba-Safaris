<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fleet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FleetController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Fleet/Index', [
            'fleet' => Fleet::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|string',
            'capacity' => 'required|string',
            'drive_type' => 'required|string',
            'features' => 'nullable|string',
            'is_available' => 'boolean',
            'drivers' => 'nullable|string',
            'suited_for' => 'nullable|string',
            'availability' => 'nullable|string',
            'price_per_hour' => 'nullable|numeric|min:0',
            'price_per_day' => 'nullable|numeric|min:0',
            'price_per_safari_drive' => 'nullable|numeric|min:0',
        ]);

        Fleet::create($validated);

        return redirect()->back()->with('success', 'New vehicle inducted into fleet.');
    }

    public function update(Request $request, Fleet $fleet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|string',
            'capacity' => 'required|string',
            'drive_type' => 'required|string',
            'features' => 'nullable|string',
            'is_available' => 'boolean',
            'drivers' => 'nullable|string',
            'suited_for' => 'nullable|string',
            'availability' => 'nullable|string',
            'price_per_hour' => 'nullable|numeric|min:0',
            'price_per_day' => 'nullable|numeric|min:0',
            'price_per_safari_drive' => 'nullable|numeric|min:0',
        ]);

        $fleet->update($validated);

        return redirect()->back()->with('success', 'Vehicle specifications updated.');
    }

    public function destroy(Fleet $fleet)
    {
        $fleet->delete();

        return redirect()->back()->with('success', 'Vehicle decommissioned from fleet.');
    }
}
