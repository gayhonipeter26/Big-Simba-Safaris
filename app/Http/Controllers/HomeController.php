<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Destination;
use App\Models\Fleet;
use App\Models\GalleryItem;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\Review;
use App\Models\Service;
use App\Models\Tour;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $heroSlides = HeroSlide::active()->get();

        // Fall back to defaults if no slides have been configured yet
        if ($heroSlides->isEmpty()) {
            $heroSlides = collect([
                ['title' => 'Maasai Mara', 'image_url' => 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2071'],
                ['title' => 'Amboseli Savanna', 'image_url' => 'https://images.unsplash.com/photo-1516422213484-21437ef33ebb?q=80&w=2670'],
                ['title' => 'Diani Beach', 'image_url' => 'https://images.unsplash.com/photo-1589315834339-ad7481ecfed9?q=80&w=2124'],
                ['title' => 'Samburu Wild', 'image_url' => 'https://images.unsplash.com/photo-1523805009345-7448845a9e53?q=80&w=2072'],
            ]);
        }

        return Inertia::render('Welcome', [
            'about' => About::where('is_active', true)->first(),
            'heroSlides' => $heroSlides,
            'featuredTours' => Tour::with(['destination'])->latest()->take(3)->get(),
            'latestPosts' => Post::where('is_published', true)->with('user:id,name')->latest()->take(3)->get(),
            'galleryItems' => GalleryItem::where('is_featured', true)->latest()->take(8)->get(),
            'featuredReviews' => Review::where('is_approved', true)->with(['user:id,name', 'tour:id,name'])->latest()->take(5)->get(),
            'favoriteSpots' => Destination::latest()->take(4)->get(),
            'fleet' => Fleet::where('is_available', true)->latest()->take(6)->get(),
            'services' => Service::latest()->get(),
        ]);
    }
}
