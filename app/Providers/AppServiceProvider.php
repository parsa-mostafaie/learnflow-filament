<?php

namespace App\Providers;

use App\Services\Leitner;
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Infolists\Infolist;
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Filament\Tables\Table;

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

        Number::useLocale(App::getLocale());
        Table::$defaultNumberLocale = App::getLocale();
        Infolist::$defaultNumberLocale = App::getLocale();

        Gate::policy(\Spatie\Activitylog\Models\Activity::class, \App\Policies\SpatieActivityPolicy::class);

        Collection::macro('cumulativeSum', function ($key = null, $sum_key = "cumulative") {
            $sum = 0;

            return $this->map(function ($item) use (&$sum, $key, $sum_key) {
                $value = is_null($key)
                    ? (is_numeric($item) ? $item : 0)
                    : data_get($item, $key, 0);

                $sum += $value;

                return match (true) {
                    is_null($key) => $sum,
                    is_array($item) => [...$item, $sum_key => $sum],
                    is_object($item) => tap(clone $item, fn($item) => $item->$sum_key = $sum),
                    default => $item, // fallback
                };
            });
        });

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
