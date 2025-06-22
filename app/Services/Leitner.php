<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use App\Models\Card;
use App\Models\CourseQuestion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class Leitner
 * 
 * This class implements the Leitner Box algorithm for spaced repetition learning.
 */
class Leitner implements Interfaces\Leitner
{
  /**
   * Update the card's stage and review date based on whether the user knows the card.
   * 
   * @param \App\Models\User $user
   * @param \App\Models\Card $card
   * @param bool $state
   * @return void
   */
  public function knowsCard($user, $card, $state)
  {
    if (is_null($card->review_date)) {
      return;
    }

    $new_stage = 1;

    if ($state) {
      $new_stage = $card->stage + 1;
    }

    $review_wait = $this->getReviewWait($new_stage);
    $review_date = $review_wait ? Carbon::now()->addHours($review_wait * $this->getDailyTaskDayLength()) : null;

    $card->update(['stage' => $new_stage, 'review_date' => $review_date]);
  }

  /**
   * Check daily tasks for the given user and course.
   * 
   * @param \App\Models\User $user
   * @param \App\Models\Course $course
   * @return void
   */
  public function checkDailyTasks($user, $course)
  {
    if ($course->isEnrolledBy($user)) {
      $dayLength = $this->getDailyTaskDayLength();
      $lastVisit = $user->enrolledCourses()->where('course_id', $course->id)->firstOrFail()->pivot->last_course_visit ?? null;

      if ($lastVisit === null || Carbon::parse($lastVisit)->diffInHours(now()) >= $dayLength) {
        // Perform the daily task
        $this->performDailyTask($user, $course);
        // Update the last visit time
        $user->enrolledCourses()->updateExistingPivot($course->id, ['last_course_visit' => now()]);
      }
    }
  }

  /**
   * Perform daily tasks for the given user and course.
   * 
   * @param \App\Models\User $user
   * @param \App\Models\Course $course
   * @return void
   */
  public function performDailyTask($user, $course)
  {
    // Calculate the number of questions already assigned to the user in stage 1
    $assignedCount = Card::where('user_id', $user->id)
      ->where('stage', 1)
      ->whereHas('courseQuestion', function ($query) use ($course) {
        $query->where('course_id', $course->id);
      })
      ->count();

    // Calculate the number of questions to assign
    $_count = min($this->getMaximumDailyTaskCount(), max(0, $this->getMaximumOfFirstStage() - $assignedCount));

    // Get questions from the course that are not yet assigned to the user
    $questions = CourseQuestion::where('course_id', $course->id)
      ->whereDoesntHave('cards', function ($query) use ($user) {
        $query->where('user_id', $user->id);
      })
      ->whereHas('question', function ($question) {
        $question->where('status', 'approved');
      })
      ->limit($_count)
      ->orderBy('id')
      ->get();

    // Assign the questions to the user
    foreach ($questions as $question) {
      Card::create([
        'user_id' => $user->id,
        'course_question_id' => $question->id,
        'review_date' => now(),
        'stage' => 1,
      ]);
    }
  }

  /**
   * Get the first card to learn for the given course and user.
   * 
   * @param \App\Models\Course $course
   * @param int $user_id
   * @return \App\Models\Card|null
   */
  public function getFirstToLearnCard($course, $user_id)
  {
    return Card::where('user_id', $user_id)
      ->whereHas('courseQuestion', function ($builder) use ($course) {
        $builder->where('course_id', $course->id);
      })
      ->whereNotNull('review_date')
      ->where('review_date', '<=', now())
      ->whereHas('courseQuestion.question', function ($question) {
        $question->where('status', 'approved');
      })
      ->orderBy('stage', 'desc')
      ->with('courseQuestion', 'courseQuestion.question', 'courseQuestion.course')
      ->first();
  }

  // Configurables

  /**
   * Get the length of a day for the daily tasks.
   * 
   * @return float
   */
  public function getDailyTaskDayLength()
  {
    return Config::float('app.leitner.day_length', 24);
  }

  /**
   * Get the maximum number of daily tasks.
   * 
   * @return int
   */
  public function getMaximumDailyTaskCount()
  {
    return Config::integer('app.leitner.max_daily_task', 5);
  }

  /**
   * Get the maximum number of cards in the first stage.
   * 
   * @return int
   */
  public function getMaximumOfFirstStage()
  {
    return Config::integer('app.leitner.max_of_first_stage', 5);
  }

  /**
   * Get the review wait times for each stage.
   * 
   * @return array
   */
  public function getReviewWaits()
  {
    return Config::array('app.leitner.review_wait_per_stage', []);
  }

  /**
   * Get the review wait time for a specific stage.
   * 
   * @param string|int $stage
   * @return mixed
   */
  public function getReviewWait($stage = '1')
  {
    return config('app.leitner.review_wait_per_stage.' . $stage, null);
  }

  /**
   * Get learned percent of course
   * 
   * @param \App\Models\Course $course
   * @param \App\Models\User $user
   * @return float|null
   */
  public function getLearnedPercentage($course, $user)
  {
    if ($course->isEnrolledBy($user)) {
      $q_sum = $course->questions_approved()->count() * (count($this->getReviewWaits()) - 1);
      $user_q_sum =
        Card::whereHas('courseQuestion', fn($builder) => $builder->where('course_id', $course->id))
          ->where('user_id', $user->id)
          ->whereHas('courseQuestion.question', function ($question) {
            $question->where('status', 'approved');
          })
          ->sum(DB::raw('stage - 1'));

      return $q_sum == 0 ? 100 : $user_q_sum / $q_sum * 100;
    }

    return null;
  }

  public function getCardsInASubbox($course, $user, $stage, $sub_box)
  {
    $per_stage = $this->getReviewWait($stage);
    $now = Carbon::now();

    return Card::where('user_id', $user->id)
      ->whereHas('courseQuestion.course', fn($q) => $q->where('id', $course->id))
      ->whereRaw("FLOOR(GREATEST(LEAST($per_stage - (TIME_TO_SEC(TIMEDIFF(review_date, ?))/({$this->getDailyTaskDayLength()} * 3600)), $per_stage), 1)) = ?", [$now->toDateTimeString(), $sub_box])
      ->where('stage', $stage)
      ->whereHas('courseQuestion.question', fn($q) => $q->where('status', 'approved'))
      ->get()
      ->count();
  }

  public function listCardsInASubbox($course, $user, $stage, $sub_box)
  {
    $per_stage = $this->getReviewWait($stage);
    $now = Carbon::now();

    return Card::where('user_id', $user->id)
      ->whereHas('courseQuestion.course', fn($q) => $q->where('id', $course->id))
      ->whereRaw("FLOOR(GREATEST(LEAST($per_stage - (TIME_TO_SEC(TIMEDIFF(review_date, ?))/({$this->getDailyTaskDayLength()} * 3600)), $per_stage), 1)) = ?", [$now->toDateTimeString(), $sub_box])
      ->where('stage', $stage)
      ->whereHas('courseQuestion.question', fn($q) => $q->where('status', 'approved'))
      ->get();
  }

  public function countCompletedCards($course, $user)
  {
    return Card::where('user_id', $user->id)
      ->whereHas('courseQuestion.course', fn($cq) => $cq->where('id', $course->id))
      ->whereHas('courseQuestion.question', fn($q) => $q->where('status', 'approved'))
      ->where('stage', count($this->getReviewWaits()) - 1)
      ->count();
  }

  public function countNotImportedCards($course, $user)
  {
    return $course->questions_approved()->count() - Card::where('user_id', $user->id)
      ->whereHas('courseQuestion.course', fn($cq) => $cq->where('id', $course->id))
      ->whereHas('courseQuestion.question', fn($q) => $q->where('status', 'approved'))
      ->count();
  }
}
