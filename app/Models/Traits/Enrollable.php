<?php

namespace App\Models\Traits;

use App\Models\CourseUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Cache;

/**
 * Trait Enrollable
 * 
 * This trait provides functionality for enrolling users in a course.
 */
trait Enrollable
{
    /**
     * Get the users that are enrolled in the course.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function enrolls()
    {
        // A course can have many enrolled users
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function getEnrollmentCacheKey(?User $user)
    {
        if ($user)
            return 'enrolled.' . static::class . ":" . $this->id . '-' . $user->id;
    }

    /**
     * Check if a user is enrolled in the course.
     * 
     * @param User|null $user
     * @return bool
     */
    public function isEnrolledBy(?User $user)
    {
        if (!$user) {
            // If no user is provided, return false
            return false;
        }

        // Check if the user is enrolled in this course
        return Cache::remember($this->getEnrollmentCacheKey($user), 60, fn() => $this->enrolls()->where('users.id', $user->id)->exists());
    }

    /**
     * Get the enrolled status for the authenticated user.
     *
     * @return bool
     */
    protected function isEnrolled(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->isEnrolledBy(Auth::user())
        )
            ->withoutObjectCaching();
    }

    /**
     * Enroll a user in the course.
     * 
     * @param User|null $user
     * @return bool
     */
    public function enroll(?User $user)
    {
        if (!$user) {
            // If no user is provided, return false
            return false;
        }

        // Attach the user to the enrolled users
        $this->enrolls()->attach($user);

        // Clear the cache for the enrollment status
        Cache::forget($this->getEnrollmentCacheKey($user));

        // Return true to indicate successful enrollment
        return true;
    }

    /**
     * Unenroll a user from the course.
     * 
     * @param User|null $user
     * @return bool
     */
    public function unenroll(?User $user)
    {
        if (!$user) {
            // If no user is provided, return false
            return false;
        }

        // Detach the user from the enrolled users
        $this->enrolls()->detach($user);

        // Clear the cache for the enrollment status
        Cache::forget($this->getEnrollmentCacheKey($user));

        $key = config("app.name") . ".enrolls_count_" . static::class . "_" . $this->id;
        request()->attributes->remove($key);

        // Return true to indicate successful unenrollment
        return true;
    }

    /**
     * Get the total number of enrolled users.
     * 
     * @return int
     */
    protected function totalEnrollmentCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->enrolls_count;
            }
        )->withoutObjectCaching();
    }

    protected function enrollsCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $key = config("app.name") . ".enrolls_count_" . static::class . "_" . $this->id;

                if (request()->attributes->has($key)) {
                    return request()->attributes->get($key);
                }

                $count = array_key_exists('enrolls_count', $this->getAttributes()) ? $this->getAttributes()['enrolls_count'] : $this->enrolls()->count();

                request()->attributes->set($key, $count);

                return $count;
            }
        )->withoutObjectCaching();
    }

    protected function formattedEnrollsCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return forhumans($this->enrolls_count);
            }
        )->withoutObjectCaching();
    }
}
