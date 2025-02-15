<?php
namespace App\Services\Interfaces;

/**
 * Interface Leitner
 * 
 * This interface defines the methods for the Leitner service.
 */
interface Leitner
{
  /**
   * Get the first card to learn for the given course and user.
   * 
   * @param mixed $course
   * @param int $user_id
   * @return mixed
   */
  public function getFirstToLearnCard($course, $user_id);

  /**
   * Check daily tasks for the given user and course.
   * 
   * @param \App\Models\User $user
   * @param mixed $course
   * @return void
   */
  public function checkDailyTasks($user, $course);

  /**
   * Record whether the user knows the card in a given state.
   * 
   * @param \App\Models\User $user
   * @param mixed $card
   * @param mixed $state
   * @return void
   */
  public function knowsCard($user, $card, $state);

  /**
   * Perform daily tasks for the given user and course.
   * 
   * @param \App\Models\User $user
   * @param mixed $course
   * @return void
   */
  public function performDailyTask($user, $course);

  /**
   * Get the length of the daily task day.
   * 
   * @return int
   */
  public function getDailyTaskDayLength();

  /**
   * Get the maximum number of daily tasks.
   * 
   * @return int
   */
  public function getMaximumDailyTaskCount();

  /**
   * Get the maximum number of cards in the first stage.
   * 
   * @return int
   */
  public function getMaximumOfFirstStage();

  /**
   * Calculate Percentage of course that have been learned
   * 
   * @return float|null
   */
  public function getLearnedPercentage($course, $user);
}
