<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TimeCalculationService;


class TimeCalculationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TimeCalculationService::class, function ($app) {
            return new TimeCalculationService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
