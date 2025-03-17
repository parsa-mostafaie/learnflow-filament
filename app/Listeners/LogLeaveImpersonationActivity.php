<?php

namespace App\Listeners;

use Lab404\Impersonate\Events\LeaveImpersonation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogLeaveImpersonationActivity
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
    public function handle(LeaveImpersonation $event): void
    {
        activity("Authentication")
            ->causedBy($event->impersonator)
            ->performedOn($event->impersonated)
            ->withProperties(['ip' => request()->ip()])
            ->event('leaved-impersonation')
            ->log("Leaved Impersonation");
    }
}
