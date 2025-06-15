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

        $eventName = User::diffRoles($event->currentRole, $event->previousRole) > 0 ? "promoted" : "demoted";

        $previousRoleName = $event->previousRole;
        $currentRoleName = $event->currentRole;

        activity()
            ->causedBy($event->causer)
            ->performedOn($event->user)
            ->event($eventName)
            ->withProperties(['old' => ['role' => $previousRoleName], 'attributes' => ['role' => $currentRoleName]])
            ->log(str($eventName)->ucfirst() . " user role from {$previousRoleName} to {$currentRoleName}");
    }
}
