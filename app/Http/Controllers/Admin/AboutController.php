<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index()
    {
        $about = About::first() ?? new About([
            'title' => 'The Spirit of Big Simba',
            'subtitle' => 'Our Legacy',
            'content' => 'Start your safari story here...',
            'is_active' => true,
        ]);

        return Inertia::render('Admin/About/Index', [
            'about' => $about,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image_1' => 'nullable|string',
            'image_2' => 'nullable|string',
            'experience_years' => 'nullable|string',
            'tours_completed' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $about = About::first() ?: new About;
        $about->fill($validated);
        $about->save();

        return redirect()->back()->with('success', 'Safari story updated successfully.');
    }
}
