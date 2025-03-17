<?php

namespace App\Listeners;

use App\Events\CourseEnrollment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogCourseEnrollmentActivity
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
    public function handle(CourseEnrollment $event): void
    {
        $eventName = $event->unenroll ? "unenrolled" : "enrolled";

        activity()
            ->causedBy($event->user)
            ->performedOn($event->course)
            ->withProperties(['ip' => request()->ip()])
            ->event($eventName)
            ->log($eventName);
    }
}
