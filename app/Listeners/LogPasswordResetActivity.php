<?php

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogPasswordResetActivity
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
    public function handle(PasswordReset $event): void
    {
        /**
         * @var \App\Models\User
         */
        $user = $event->user;

        activity("authentication")
            ->causedBy(null)
            ->performedOn($user)
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'email' => $user->email,
            ])
            ->event('password-reset')
            ->log("Password reset");
    }
}
