<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

/**
 * Class VoltServiceProvider
 * 
 * This service provider is responsible for registering and bootstrapping services related to Livewire Volt.
 */
class VoltServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 
     * This method is called to register services in the container.
     */
    public function register(): void
    {
        // No services registered for now
    }

    /**
     * Bootstrap any application services.
     * 
     * This method is called to bootstrap services, like mounting Volt components.
     */
    public function boot(): void
    {
        Volt::mount([
            config('livewire.view_path', resource_path('views/livewire')),
            resource_path('views/pages'),
        ]);
    }
}
