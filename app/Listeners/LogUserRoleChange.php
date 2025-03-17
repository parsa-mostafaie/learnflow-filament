<?php

namespace App\Listeners;

use App\Events\UserRoleChanged;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserRoleChange
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
    public function handle(UserRoleChanged $event): void
    {
        if ($event->currentRole == $event->previousRole)
            return;

        $eventName = $event->currentRole > $event->previousRole ? "promoted" : "demoted";

        $previousRoleName = array_flip(User::roles)[$event->previousRole];
        $currentRoleName = array_flip(User::roles)[$event->currentRole];

        activity()
            ->causedBy($event->causer)
            ->performedOn($event->user)
            ->event($eventName)
            ->withProperties(['old' => ['role' => $previousRoleName], 'attributes' => ['role' => $currentRoleName]])
            ->log($eventName);
    }
}
