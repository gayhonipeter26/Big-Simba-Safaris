<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index()
    {
        return Inertia::render('Gallery', [
            'items' => GalleryItem::latest()->get(),
        ]);
    }
}
