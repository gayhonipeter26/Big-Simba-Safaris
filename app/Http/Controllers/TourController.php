<?php

namespace App\Http\Controllers;

use App\Models\Fleet;
use App\Models\Service;
use App\Models\Tour;
use Inertia\Inertia;
use Inertia\Response;

class TourController extends Controller
{
    public function index(): Response
    {
        $search = request('search');

        $tours = Tour::with('destination')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('destination', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->get();

        return Inertia::render('Tours/Index', [
            'tours' => $tours,
            'fleet' => Fleet::where('is_available', true)->get(),
            'services' => Service::orderBy('order')->get(),
        ]);
    }

    public function show(string $slug): Response
    {
        $tour = Tour::with(['destination', 'reviews' => function ($query) {
            $query->where('is_approved', true)->with('user:id,name')->latest();
        }])->where('slug', $slug)->firstOrFail();

        return Inertia::render('Tours/Show', [
            'tour' => $tour,
            'fleet' => Fleet::where('is_available', true)->get(),
        ]);
    }
}
