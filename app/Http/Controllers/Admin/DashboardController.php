<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\FleetHire;
use App\Models\GalleryItem;
use App\Models\HeroSlide;
use App\Models\Post;
use App\Models\Review;
use App\Models\Tour;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'totalRevenue' => Booking::where('status', 'confirmed')->sum('total_price'),
            'totalBookings' => Booking::count(),
            'activeTours' => Tour::count(),
            'totalUsers' => User::count(),
            'totalPosts' => Post::count(),
            'totalGalleryItems' => GalleryItem::count(),
            'totalHeroSlides' => HeroSlide::count(),
            'pendingContent' => [
                'reviews' => Review::where('is_approved', false)->count(),
                'inquiries' => ContactMessage::where('status', 'pending')->count(),
                'fleetHires' => FleetHire::where('status', 'pending')->count(),
            ],
            'recentBookings' => Booking::with(['user', 'tour'])->latest()->limit(8)->get(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
        ]);
    }
}
