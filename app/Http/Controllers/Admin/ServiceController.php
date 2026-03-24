<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Services/Index', [
            'services' => Service::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'pricing_tier' => 'nullable|string',
            'starting_price' => 'nullable|numeric|min:0',
            'features' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(5);

        Service::create($validated);

        return redirect()->back()->with('success', 'Extended service unit activated.');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'pricing_tier' => 'nullable|string',
            'starting_price' => 'nullable|numeric|min:0',
            'features' => 'nullable|string',
        ]);

        $service->update($validated);

        return redirect()->back()->with('success', 'Service specifications recalibrated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->back()->with('success', 'Service unit decommissioned.');
    }
}
