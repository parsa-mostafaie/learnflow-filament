<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Attempting;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogAttemptingActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Attempting $event): void
    {
        activity("authentication")
            ->causedBy(null)
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'remember' => $event->remember,
                'guard' => $event->guard,
                'email' => $event->credentials['email'] ?? 'unknown'
            ])
            ->event('attempting')
            ->log("Attempting to log in");
    }
}
