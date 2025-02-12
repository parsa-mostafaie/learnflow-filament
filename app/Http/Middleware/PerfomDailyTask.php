<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Course, App\Models\CourseQuestion, App\Models\Card;
use Illuminate\Support\Facades\Config;

/**
 * Class PerfomDailyTask
 * 
 * This middleware is responsible for performing daily tasks for the authenticated user.
 */
class PerfomDailyTask
{
    /**
     * Handle an incoming request.
     *
     * This method checks if the user has daily tasks to be performed for the given course.
     * If there are tasks, it performs them and then passes the request to the next middleware or controller.
     *
     * @param Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        /**
         * @var \App\Models\User
         * 
         * Get the authenticated user.
         */
        $user = Auth::user();

        // Get the course slug from the route
        $courseSlug = $request->route('id');

        // Find the course with the given slug, including soft-deleted ones
        $course = Course::withTrashed()->where('slug', $courseSlug)->firstOrFail();

        // Perform daily tasks for the user and the course
        $course->checkDailyTasks($user);

        // Pass the request to the next middleware or controller
        return $next($request);
    }
}
