<?php

namespace App\Models\Traits;

use App\Facades\Leitner;
use App\Models\Card;
use App\Models\CourseQuestion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

trait HasDailyTasks
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootHasDailyTasks()
    {
        /** Nothing for now */
    }

    public function checkDailyTasks($user)
    {
        Leitner::checkDailyTasks($user, $this);
    }
}
