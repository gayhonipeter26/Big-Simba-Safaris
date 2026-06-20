<?php

namespace App\Providers;

use App\Socialite\TikTokProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Socialite::extend('tiktok', function ($app) {
            $config = $app['config']['services.tiktok'];

            return Socialite::buildProvider(TikTokProvider::class, $config);
        });
    }
}
