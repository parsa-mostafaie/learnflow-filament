<?php

namespace App\Policies;

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
        // All users can view any questions
        return true;
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
        // All users can view a specific question
        return true;
    }

    /**
     * Determine whether the user can create questions.
     * 
     * @param User|null $user
     * @return bool
     */
    public function create(?User $user): bool
    {
        // Only authenticated admins can create questions
        return (!!$user) && $user->isRole('admin');
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
        // Developers or the question author can update the question
        return (!!$user) && ($user->isRole('developer') || $question->user->is($user));
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
        // Deletion permission follows the update permission
        return $this->update($user, $question);
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
        // Restoration permission follows the update permission
        return $this->update($user, $question);
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
        // Permanent deletion permission follows the deletion permission
        return $this->delete($user, $question);
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
        return $user->isRole('developer');
    }
}
