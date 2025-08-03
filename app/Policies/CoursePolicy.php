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
        return (!!$user) && (!$course->trashed() || $course->author()->is($user) || $user->can('enroll to all courses'));
    }

    /**
     * Determine whether the user can view any courses.
     * 
     * @param User|null $user
     * @return bool
     */
    public function viewAny(?User $user): bool
    {
        return $this->manage($user);
    }

    public function viewOverview(?User $user): bool
    {
        return (!!$user) && ($user->can('view courses overview'));
    }

    public function manage(?User $user): bool
    {
        return (!!$user) && $user->can('manage any courses');
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
        if ($user && $user->can('view all courses')) {
            return true;
        }

        return (!$course->trashed()) || $course->author()->is($user);
    }

    /**
     * Determine whether the user can create courses.
     * 
     * @param User|null $user
     * @return bool
     */
    public function create(?User $user): bool
    {
        return $user && $user->can('create courses');
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
        return (!!$user) && ($user->can('update all courses') || $course->author()->is($user));
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
        return (!!$user) && ($user->can('see all courses enrolls') || $course->author()->is($user));
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
        return (!!$user) && ($user->can('assign to all courses') || $course->author()->is($user));
    }

    public function attachQuestion(?User $user, Course $course): bool
    {
        return $this->assign($user, $course);
    }

    public function detachQuestion(?User $user, Course $course): bool
    {
        return $this->attachQuestion($user, $course);
    }

    public function attachAnyQuestion(?User $user, Course $course): bool
    {
        return $this->assignMany($user, $course);
    }

    public function detachAnyQuestion(?User $user, Course $course): bool
    {
        return $this->attachAnyQuestion($user, $course);
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
        return (!!$user) && ($user->can('assign many questions to all courses') || $course->author()->is($user));
    }

    public function assignAny(?User $user, Course $course): bool
    {
        return $this->assignMany($user, $course) || $this->assign($user, $course);
    }

    public function getReport(?User $user, Course $course): bool
    {
        return $user && $course->isEnrolledBy($user);
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
        return (!!$user) && ($user->can('delete all courses') || $course->author()->is($user));
    }

    public function deleteAny(?User $user): bool
    {
        return (!!$user) && ($user->can('bulk delete courses'));
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
        return (!!$user) && ($user->can('restore all courses') || $course->author()->is($user));
    }

    public function restoreAny(?User $user): bool
    {
        // Restoration permission follows the deletion permission
        return (!!$user) && ($user->can('bulk restore courses'));
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
        return (!!$user) && ($user->can('force delete all courses') || $course->author()->is($user));
    }

    public function forceDeleteAny(?User $user): bool
    {
        return (!!$user) && ($user->can('bulk force delete courses'));
    }
}
