<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrategicEvent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StrategicEventController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Events/Index', [
            'events' => StrategicEvent::latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Events/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'occasion' => 'nullable|string|max:255',
            'month' => 'required|integer|min:0|max:11',
            'days' => 'required|array',
            'color' => 'required|string|max:50',
            'label' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'time' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'asset_class' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
        ]);

        StrategicEvent::create($validated);

        return redirect()->route('admin.strategic-events.index')->with('success', 'Event successfully scheduled.');
    }

    public function edit(StrategicEvent $strategicEvent)
    {
        return Inertia::render('Admin/Events/Edit', [
            'strategicEvent' => $strategicEvent,
        ]);
    }

    public function update(Request $request, StrategicEvent $strategicEvent)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'occasion' => 'nullable|string|max:255',
            'month' => 'required|integer|min:0|max:11',
            'days' => 'required|array',
            'color' => 'required|string|max:50',
            'label' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'time' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'asset_class' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
        ]);

        $strategicEvent->update($validated);

        return redirect()->route('admin.strategic-events.index')->with('success', 'Event intelligence updated.');
    }

    public function destroy(StrategicEvent $strategicEvent)
    {
        $strategicEvent->delete();

        return redirect()->route('admin.strategic-events.index')->with('success', 'Event erased from timeline.');
    }
}
