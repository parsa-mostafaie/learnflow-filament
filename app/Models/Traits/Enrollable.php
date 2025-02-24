<?php

namespace App\Models\Traits;

use App\Models\CourseUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        return $this->enrolls()->where('users.id', $user->id)->exists();
    }

    /**
     * Get the enrolled status for the authenticated user.
     * 
     * @return bool
     */
    public function getIsEnrolledAttribute()
    {
        return $this->isEnrolledBy(Auth::user());
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

        // Return true to indicate successful unenrollment
        return true;
    }

    /**
     * Get the total number of enrolled users.
     * 
     * @return int
     */
    public function getTotalEnrollmentCountAttribute()
    {
        return $this->enrolls()->count();
    }
}
