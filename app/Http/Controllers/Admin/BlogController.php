<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Blog/Index', [
            'posts' => Post::with('user:id,name')->latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Blog/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $request->user()->posts()->create($validated);

        return redirect()->route('admin.blog.index')->with('success', 'Safari story chronicled.');
    }

    public function edit(Post $post)
    {
        return Inertia::render('Admin/Blog/Edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('posts', 'slug')->ignore($post->id)],
            'content' => 'required|string',
            'image' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $post->update($validated);

        return redirect()->route('admin.blog.index')->with('success', 'The story has been refined.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.blog.index')->with('success', 'The chronicle has been removed.');
    }
}
