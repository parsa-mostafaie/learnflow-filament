<?php

namespace App\Listeners;

use Lab404\Impersonate\Events\TakeImpersonation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTakeImpersonationActivity
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
    public function handle(TakeImpersonation $event): void
    {
        activity("Authentication")
            ->causedBy($event->impersonator)
            ->performedOn($event->impersonated)
            ->withProperties(['ip' => request()->ip()])
            ->event('impersonated')
            ->log("Taked Impersonation");
    }
}
