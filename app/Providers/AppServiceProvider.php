<?php

namespace App\Providers;

use App\Services\AdsSettings;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        // Share ads settings with every Inertia page via $page.props.ads
        Inertia::share([
            'ads' => fn () => AdsSettings::get(),
        ]);
    }
}
