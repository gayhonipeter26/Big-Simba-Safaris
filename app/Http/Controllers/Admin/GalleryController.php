<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Gallery/Index', [
            'items' => GalleryItem::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video',
            'url' => 'required|url',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        GalleryItem::create($validated);

        return redirect()->back()->with('success', 'Item added to gallery.');
    }

    public function update(Request $request, GalleryItem $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video',
            'url' => 'required|url',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $gallery->update($validated);

        return redirect()->back()->with('success', 'Gallery item updated.');
    }

    public function destroy(GalleryItem $gallery)
    {
        $gallery->delete();

        return redirect()->back()->with('success', 'Gallery item removed.');
    }
}
