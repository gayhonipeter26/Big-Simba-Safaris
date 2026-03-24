<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FleetController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\FleetHireController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TourController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public Guest-Accessible Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/tours/{slug}', [TourController::class, 'show'])->name('tours.show');
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{slug}', [DestinationController::class, 'show'])->name('destinations.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/about', fn () => redirect('/#about'))->name('about');
Route::get('/contact', fn () => redirect('/#contact'))->name('contact');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Public Action Routes
Route::get('fleet-hire', [FleetHireController::class, 'index'])->name('fleet-hire.index');
Route::post('fleet-hire', [FleetHireController::class, 'store'])->name('fleet-hire.store');
Route::post('contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/tours/{tour}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Private User & Dashboard-Consolidated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard', [
            'bookings' => auth()->user()->bookings()->with('tour.destination')->latest()->get(),
        ]);
    })->name('dashboard');

    // Dashboard specific aliases for user portal consistency
    Route::group(['prefix' => 'dashboard', 'as' => 'user.'], function () {
        Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
        Route::get('/destinations/{slug}', [DestinationController::class, 'show'])->name('destinations.show');
        Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
        Route::get('/tours/{slug}', [TourController::class, 'show'])->name('tours.show');
        Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
    });
});

// Operations Center (Strictly Admin)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('tours', App\Http\Controllers\Admin\TourController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('destinations', App\Http\Controllers\Admin\DestinationController::class);
    Route::get('about', [AboutController::class, 'index'])->name('about.index');
    Route::post('about', [AboutController::class, 'update'])->name('about.update');
    Route::resource('blog', App\Http\Controllers\Admin\BlogController::class)->parameters(['blog' => 'post']);
    Route::resource('users', UserController::class);
    Route::resource('gallery', App\Http\Controllers\Admin\GalleryController::class);
    Route::resource('fleet', FleetController::class);
    Route::get('fleet-hires', [App\Http\Controllers\Admin\FleetHireController::class, 'index'])->name('fleet-hires.index');
    Route::patch('fleet-hires/{fleetHire}', [App\Http\Controllers\Admin\FleetHireController::class, 'update'])->name('fleet-hires.update');
    Route::delete('fleet-hires/{fleetHire}', [App\Http\Controllers\Admin\FleetHireController::class, 'destroy'])->name('fleet-hires.destroy');
    Route::resource('contact-messages', ContactMessageController::class);
    Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'update', 'destroy']);
    Route::resource('hero-slides', HeroSlideController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('services', ServiceController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
