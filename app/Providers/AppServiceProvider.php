<?php

namespace App\Providers;

use App\Services\HistoricalData\HistoricalDataService;
use App\Services\NasdaqListings\NasdaqListingsService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->singleton(
            NasdaqListingsService::class,
            fn() => new NasdaqListingsService(
                config('services.nasdaqListings.uri')
            )
        );

        $this->app->singleton(
            HistoricalDataService::class,
            fn() => new HistoricalDataService(
                config('services.historicalData.uri'),
                config('services.historicalData.key'),
                config('services.historicalData.host')
            )
        );
    }
}
