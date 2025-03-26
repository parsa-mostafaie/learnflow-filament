<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Class CoursePolicy
 * 
 * This policy determines the various permissions for users regarding course models.
 */
class CoursePolicy
{
    /**
     * Determine whether the user can enroll in courses.
     * 
     * @param User|null $user
     * @param Course $course
     * @return bool
     */
    public function enroll(?User $user, Course $course): bool
    {
        // User must be authenticated to enroll in a course
        return !!$user;
    }

    /**
     * Determine whether the user can view any courses.
     * 
     * @param User|null $user
     * @return bool
     */
    public function viewAny(?User $user): bool
    {
        // All users can view any courses
        return true;
    }

    /**
     * Determine whether the user can view a specific course.
     * 
     * @param User|null $user
     * @param Course $course
     * @return bool
     */
    public function view(?User $user, Course $course): bool
    {
        if (!$user)
            return false;

        // Developers can view any course
        if ($user->isRole('developer')) {
            return true;
        }

        // Users can view a course if it is not soft-deleted or if they are the author
        return !$course->trashed() || $course->user_id == $user->id;
    }

    /**
     * Determine whether the user can create courses.
     * 
     * @param User|null $user
     * @return bool
     */
    public function create(?User $user): bool
    {
        // Only admins can create courses
        return $user->isRole('admin');
    }

    /**
     * Determine whether the user can update a specific course.
     * 
     * @param User|null $user
     * @param Course $course
     * @return bool
     */
    public function update(?User $user, Course $course): bool
    {
        // Developers or the course author can update the course
        return (!!$user) && ($user->isRole('developer') || $course->user->is($user));
    }

    /**
     * Determine whether the user can manage enrollments of a specific course.
     * 
     * @param User|null $user
     * @param Course $course
     * @return bool
     */
    public function seeEnrolls(?User $user, Course $course): bool
    {
        // Developers or the course author can update the course
        return $this->update($user, $course);
    }

    /**
     * Determine whether the user can assign to a specific course.
     * 
     * @param User|null $user
     * @param Course $course
     * @return bool
     */
    public function assign(?User $user, Course $course): bool
    {
        // Assignment permission follows the update permission
        return $this->update($user, $course);
    }

    /**
     * Determine whether the user can assign file to a specific course.
     * 
     * @param User|null $user
     * @param Course $course
     * @return bool
     */
    public function assignMany(?User $user, Course $course): bool
    {
        return $this->assign($user, $course);
    }

    public function assignAny(?User $user, Course $course): bool
    {
        return $this->assignMany($user, $course) || $this->assign($user, $course);
    }

    /**
     * Determine whether the user can delete a specific course.
     * 
     * @param User|null $user
     * @param Course $course
     * @return bool
     */
    public function delete(?User $user, Course $course): bool
    {
        // Deletion permission follows the update permission
        return $this->update($user, $course);
    }

    /**
     * Determine whether the user can restore a specific course.
     * 
     * @param User|null $user
     * @param Course $course
     * @return bool
     */
    public function restore(?User $user, Course $course): bool
    {
        // Restoration permission follows the deletion permission
        return $this->delete($user, $course);
    }

    /**
     * Determine whether the user can permanently delete a specific course.
     * 
     * @param User|null $user
     * @param Course $course
     * @return bool
     */
    public function forceDelete(?User $user, Course $course): bool
    {
        // Permanent deletion permission follows the deletion permission
        return $this->delete($user, $course);
    }
}
