<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('Blog/Index', [
            'posts' => Post::where('is_published', true)->with('user:id,name')->latest()->get(),
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->with('user:id,name')->firstOrFail();

        return Inertia::render('Blog/Show', [
            'post' => $post,
            'recentPosts' => Post::where('is_published', true)->where('id', '!=', $post->id)->latest()->take(3)->get(),
        ]);
    }
}
