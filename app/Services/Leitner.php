<?php
namespace App\Services;

use Illuminate\Support\Facades\Config;
use App\Models\Card;
use App\Models\CourseQuestion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Leitner implements Interfaces\Leitner
{
  public function knowsCard($user, $card, $state)
  {
    $new_stage = 1;

    if ($state) {
      $new_stage = $card->stage + 1;
    }

    $review_wait = $this->getReviewWait($new_stage);
    $review_date = $review_wait ? Carbon::now()->addHours($review_wait * $this->getDailyTaskDayLength()) : null;

    $card->update(['stage' => $new_stage, 'review_date' => $review_date]);
  }

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

  public function performDailyTask($user, $course)
  {
    // Calculate the number of questions already assigned to the user in stage 1
    $assignedCount = Card::where('user_id', $user->id)
      ->where('stage', 1)
      ->whereHas('courseQuestion', function ($query) use ($course) {
        $query->where('course_id', $course->id);
      })
      ->count();

    // Calculate $_count
    $_count = min($this->getMaximumDailyTaskCount(), max(0, $this->getMaximumOfFirstStage() - $assignedCount));

    // Get questions from the course that are not yet assigned to the user
    $questions = CourseQuestion::where('course_id', $course->id)
      ->whereDoesntHave('cards', function ($query) use ($user) {
        $query->where('user_id', $user->id);
      })
      ->limit($_count)
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

  public function getFirstToLearnCard($course, $user_id)
  {
    return Card::where('user_id', $user_id)
      ->whereHas('courseQuestion', function ($builder) use ($course) {
        $builder->where('course_id', $course->id);
      })
      ->whereNotNull('review_date')
      ->where('review_date', '<=', now())
      ->orderBy('stage', 'desc')
      ->with('courseQuestion', 'courseQuestion.question', 'courseQuestion.course')
      ->first();
  }

  // Configurables
  public function getDailyTaskDayLength()
  {
    return Config::float('app.leitner.day_length', 24);
  }

  public function getMaximumDailyTaskCount()
  {
    return Config::integer('app.leitner.max_daily_task', 5);
  }

  public function getMaximumOfFirstStage()
  {
    return Config::integer('app.leitner.max_of_first_stage', 5);
  }

  public function getReviewWaits()
  {
    return Config::array('app.leitner.review_wait_per_stage', []);
  }

  public function getReviewWait($stage = '1')
  {
    return config('app.leitner.review_wait_per_stage.' . $stage, null);
  }
}