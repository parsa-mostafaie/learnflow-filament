<?php

namespace App\Models\Traits;

use App\Facades\Leitner;
use App\Models\Card;
use App\Models\CourseQuestion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

/**
 * Trait HasDailyTasks
 * 
 * This trait provides functionality for checking and performing daily tasks for a user.
 */
trait HasDailyTasks
{
    /**
     * The "booted" method of the model.
     * 
     * This method is called when the model is booted. Currently, it does not perform any actions.
     *
     * @return void
     */
    protected static function bootHasDailyTasks()
    {
        // No actions are performed for now
    }

    /**
     * Check and perform daily tasks for the given user.
     * 
     * This method delegates the task checking and execution to the Leitner service.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function checkDailyTasks($user)
    {
        Leitner::checkDailyTasks($user, $this);
    }
}
