<?php

namespace App\Models\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait Enrollable
{
    public function enrolls()
    {
        return $this->belongsToMany(User::class)->withTimestamps(); // A course can have many enrolled users
    }

    public function isEnrolledBy(?User $user)
    {
        if (!$user) {
            return false; // If no user is provided, return false
        }

        // Check if the user is enrolled in this course
        return $this->enrolls()->where('users.id', $user->id)->exists();
    }

    public function getIsEnrolledAttribute()
    {
        return $this->isEnrolledBy(Auth::user());
    }

    public function enroll(?User $user)
    {
        if (!$user) {
            return false; // If no user is provided, return false
        }

        $this->enrolls()->attach($user); // Attach the user to the enrolled users

        return true; // Return true to indicate successful enrollment
    }

    public function unenroll(?User $user)
    {
        if (!$user) {
            return false; // If no user is provided, return false
        }

        // Detach the user from the enrolled users
        $this->enrolls()->detach($user);

        return true; // Return true to indicate successful unenrollment
    }

    public function getTotalEnrollmentCountAttribute()
    {
        return $this->enrolls()->count();
    }
}
