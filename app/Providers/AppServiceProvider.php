<?php

namespace App\Providers;

use App\Services\Leitner;
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use Illuminate\Support\Facades\Gate;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * 
 * This service provider is responsible for registering and bootstrapping application services.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 
     * This method registers the Leitner service as a singleton in the application container
     * and creates an alias for the service.
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
     * 
     * This method is called after all other services have been registered and is used for
     * initializing or bootstrapping any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        Gate::policy(\Spatie\Activitylog\Models\Activity::class, \App\Policies\SpatieActivityPolicy::class);

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            // $switch
            //     ->locales(['en', 'fa'])
            //     ->flags([
            //         'fa' => asset('flags/ir.svg'),
            //         'en' => asset('flags/us.svg'),
            //     ])
            //     ->displayLocale('fa') // Sets Farsi as the language for label localization
            //     ->circular();
        });
    }
}
