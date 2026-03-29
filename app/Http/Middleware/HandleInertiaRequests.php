<?php

namespace App\Http\Middleware;

use App\Models\Tour;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;
use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return array_merge(parent::share($request), [
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'tours' => Tour::select('id', 'name')->get(),
            'currency' => [
                'current' => session('currency', 'KES'),
                'rate' => session('currency', 'KES') === 'KES' ? 1 : Cache::remember('rate_KES_'.session('currency'), 3600, function () {
                    return CurrencyConverter::convert(1)->from('KES')->to(session('currency'))->get();
                }),
                'symbol' => session('currency', 'KES') === 'KES' ? 'Ksh' : (session('currency') === 'USD' ? '$' : session('currency')),
            ],
            'locale' => [
                'current' => app()->getLocale(),
                'allowed' => app('language')->allowed(),
            ],
        ]);
    }
}
