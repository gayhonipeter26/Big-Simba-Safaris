<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TourController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Tours/Index', [
            'tours' => Tour::with('destination')->latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Tours/Create', [
            'destinations' => Destination::select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tours,slug',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
            'itinerary' => 'required|array',
            'images' => 'required|array',
            'whats_included' => 'required|array',
            'whats_excluded' => 'required|array',
        ]);

        Tour::create($validated);

        return to_route('admin.tours.index')->with('success', 'Tour created successfully.');
    }

    public function edit(Tour $tour): Response
    {
        return Inertia::render('Admin/Tours/Edit', [
            'tour' => $tour,
            'destinations' => Destination::select('id', 'name')->get(),
        ]);
    }

    public function update(Request $request, Tour $tour): RedirectResponse
    {
        $validated = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('tours')->ignore($tour->id)],
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:100',
            'itinerary' => 'required|array',
            'images' => 'required|array',
            'whats_included' => 'required|array',
            'whats_excluded' => 'required|array',
        ]);

        $tour->update($validated);

        return to_route('admin.tours.index')->with('success', 'Tour updated successfully.');
    }

    public function destroy(Tour $tour): RedirectResponse
    {
        $tour->delete();

        return to_route('admin.tours.index')->with('success', 'Tour deleted successfully.');
    }
}
