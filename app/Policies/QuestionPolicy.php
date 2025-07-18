<?php

namespace App\Policies;

use App\Enums\Status;
use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Class QuestionPolicy
 * 
 * This policy determines the various permissions for users regarding question models.
 */
class QuestionPolicy
{
    /**
     * Determine whether the user can view any questions.
     * 
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return (!!$user) && $user->can('manage any questions');
    }

    /**
     * Determine whether the user can view a specific question.
     * 
     * @param User $user
     * @param Question $question
     * @return bool
     */
    public function view(User $user, Question $question): bool
    {
        if ($user && $user->can('view all questions')) {
            return true;
        }

        return ($question->isStatus(Status::Approved)) || $question->author()->is($user);
    }

    /**
     * Determine whether the user can create questions.
     * 
     * @param User|null $user
     * @return bool
     */
    public function create(?User $user): bool
    {
        return $user && $user->can('create questions');
    }

    /**
     * Determine whether the user can update a specific question.
     * 
     * @param User|null $user
     * @param Question $question
     * @return bool
     */
    public function update(?User $user, Question $question): bool
    {
        return (!!$user) && ($user->can('update all questions') || $question->author()->is($user));
    }

    /**
     * Determine whether the user can delete a specific question.
     * 
     * @param User|null $user
     * @param Question $question
     * @return bool
     */
    public function delete(?User $user, Question $question): bool
    {
        return (!!$user) && ($user->can('delete all questions') || $question->author()->is($user));
    }

    public function deleteAny(?User $user): bool
    {
        return (!!$user) && ($user->can('bulk delete questions'));
    }

    /**
     * Determine whether the user can restore a specific question.
     * 
     * @param User|null $user
     * @param Question $question
     * @return bool
     */
    public function restore(?User $user, Question $question): bool
    {
        return (!!$user) && ($user->can('restore all questions') || $question->author()->is($user));
    }

    public function restoreAny(?User $user): bool
    {
        // Restoration permission follows the deletion permission
        return (!!$user) && ($user->can('bulk restore questions'));
    }


    /**
     * Determine whether the user can permanently delete a specific question.
     * 
     * @param User|null $user
     * @param Question $question
     * @return bool
     */
    public function forceDelete(?User $user, Question $question): bool
    {
        return (!!$user) && ($user->can('force delete all questions') || $question->author()->is($user));
    }

    /**
     * Determine whether the user can approve/reject/pend a specific question.
     * 
     * @param User|null $user
     * @param Question $question
     * @return bool
     */
    public function changeStatus(?User $user, Question $question): bool
    {
        return $user && $user->can('change questions state');
    }

    public function changeStatusAny(?User $user): bool
    {
        return $user && $user->can('bulk change questions state');
    }

    public function approve(User $user, Question $question): bool
    {
        return $this->changeStatus($user, $question);
    }

    public function approveAny(User $user): bool
    {
        return $this->changeStatusAny($user);
    }

    public function reject(User $user, Question $question): bool
    {
        return $this->changeStatus($user, $question);
    }

    public function rejectAny(User $user): bool
    {
        return $this->changeStatusAny($user);
    }

    public function pending(User $user, Question $question): bool
    {
        return $this->changeStatus($user, $question);
    }

    public function pendingAny(User $user): bool
    {
        return $this->changeStatusAny($user);
    }

    public function forceDeleteAny(?User $user): bool
    {
        return (!!$user) && ($user->can('bulk force delete questions'));
    }
}
