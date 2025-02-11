<?php

namespace App\Providers;

use App\Services\Leitner;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Leitner::class, function ($app) {
            return new Leitner();
        });

        $this->app->alias(Leitner::class, 'leitner');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
