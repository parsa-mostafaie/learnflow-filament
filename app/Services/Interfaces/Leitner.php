<?php
namespace App\Services\Interfaces;

interface Leitner
{
  public function getFirstToLearnCard($course, $user_id);
  public function checkDailyTasks($user, $course);
  public function knowsCard($user, $card, $state);
  public function performDailyTask($user, $course);
  public function getDailyTaskDayLength();
  public function getMaximumDailyTaskCount();
  public function getMaximumOfFirstStage();
}