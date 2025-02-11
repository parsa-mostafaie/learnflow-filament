<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can enroll models.
     */
    public function enroll(?User $user, Course $course): bool
    {
        return !!$user;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Course $course): bool
    {
        if (!$user) return false;

        // Check if the user is a developer
        if ($user->isRole('developer')) {
            return true;
        }

        // Check if the model is not soft-deleted or if the user is the author
        return !$course->trashed() || $course->user_id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return $user->isRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Course $course): bool
    {
        return (!!$user) && ($user->isRole('developer') || $course->user->is($user));
    }

    /**
     * Determine whether the user can assign to the model.
     */
    public function assign(?User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(?User $user, Course $course): bool
    {
        return $this->delete($user, $course);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(?User $user, Course $course): bool
    {
        return $this->delete($user, $course);
    }
}
