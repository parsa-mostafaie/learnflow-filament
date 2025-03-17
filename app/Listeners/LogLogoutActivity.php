<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogLogoutActivity
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
    public function handle(Logout $event): void
    {
        /**
         * @var \App\Models\User
         */
        $user = $event->user;

        activity("Authentication")
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties(['ip' => request()->ip(), 'guard' => $event->guard])
            ->event('Log Out')
            ->log("Log Out");
    }
}
