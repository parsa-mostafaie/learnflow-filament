<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Course, App\Models\CourseQuestion, App\Models\Card;
use Illuminate\Support\Facades\Config;

class PerfomDailyTask
{
    public function handle($request, Closure $next)
    {
        /**
         * @var \App\Models\User
         */
        $user = Auth::user();
        $courseSlug = $request->route('id');
        $course = Course::withTrashed()->where('slug', $courseSlug)->firstOrFail();

        $course->checkDailyTasks($user);

        return $next($request);
    }
}
