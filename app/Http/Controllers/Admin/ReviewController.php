<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Reviews/Index', [
            'reviews' => Review::with(['user:id,name', 'tour:id,name'])->latest()->get(),
        ]);
    }

    public function update(Request $request, Review $review)
    {
        $review->update([
            'is_approved' => $request->is_approved,
        ]);

        return back()->with('success', $review->is_approved ? 'Review visible to the pride.' : 'Review retracted.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'The review has been purged.');
    }
}
