<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FleetHire;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FleetHireController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Fleet/Hires', [
            'hires' => FleetHire::with(['fleet', 'user:id,name'])->latest()->get(),
        ]);
    }

    public function update(Request $request, FleetHire $fleetHire)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected,completed',
        ]);

        $fleetHire->update($validated);

        return redirect()->back()->with('success', 'Deployment status updated.');
    }

    public function destroy(FleetHire $fleetHire)
    {
        $fleetHire->delete();

        return redirect()->back()->with('success', 'Deployment record purged.');
    }
}
