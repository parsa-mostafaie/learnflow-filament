<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogAuthenticatedActivity
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
    public function handle(Authenticated $event): void
    {
        // TODO: Uncomment the logic to log the authenticated activity, after a full-research for that its best-option should be logged or not.
        /**
         * @var \App\Models\User
         */
        // $user = $event->user;

        // activity("authentication")
        //     ->causedBy($user)
        //     ->performedOn($user)
        //     ->withProperties([
        //         'ip' => request()->ip(),
        //         'user_agent' => request()->userAgent(),
        //         'guard' => $event->guard
        //     ])
        //     ->event('authenticated')
        //     ->log("Authenticated successfully");
    }
}
