<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $tour->reviews()->create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'] ?? $request->user()?->name,
            'email' => $validated['email'] ?? $request->user()?->email,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => false, // Requires moderation
        ]);

        return back()->with('success', 'Your review has been submitted for moderation.');
    }
}
