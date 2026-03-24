<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class DestinationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Destinations/Index', [
            'destinations' => Destination::latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Destinations/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:destinations,slug',
            'overview' => 'required|string',
            'image' => 'required|string',
        ]);

        Destination::create($validated);

        return to_route('admin.destinations.index')->with('success', 'Destination created successfully.');
    }

    public function edit(Destination $destination): Response
    {
        return Inertia::render('Admin/Destinations/Edit', [
            'destination' => $destination,
        ]);
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('destinations')->ignore($destination->id)],
            'overview' => 'required|string',
            'image' => 'required|string',
        ]);

        $destination->update($validated);

        return to_route('admin.destinations.index')->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $destination->delete();

        return to_route('admin.destinations.index')->with('success', 'Destination deleted successfully.');
    }
}
