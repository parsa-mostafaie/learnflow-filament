<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogLoginActivity
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
    public function handle(Login $event): void
    {

        /**
         * @var \App\Models\User
         */
        $user = $event->user;

        activity("Authentication")
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties(['ip' => request()->ip(), 'remember' => $event->remember, 'guard' => $event->guard])
            ->event('Login')
            ->log("Login");
    }
}
