<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Leitner
 * 
 * This is the facade for the Leitner service.
 * 
 * The facade provides a static interface to the underlying Leitner service,
 * which is responsible for implementing the actual functionality.
 * 
 * @method static void knowsCard(\App\Models\User $user, \App\Models\Card $card, bool $state)
 * @method static void checkDailyTasks(\App\Models\User $user, \App\Models\Course $course)
 * @method static void performDailyTask(\App\Models\User $user, \App\Models\Course $course)
 * @method static \App\Models\Card|null getFirstToLearnCard(\App\Models\Course $course, int $user_id)
 * @method static float getDailyTaskDayLength()
 * @method static int getMaximumDailyTaskCount()
 * @method static int getMaximumOfFirstStage()
 * @method static array getReviewWaits()
 * @method static mixed getReviewWait(string|int $stage = '1')
 * @method static float|null getLearnedPercentage(\App\Models\Course $course, \App\Models\User $user)
 * @method static int|null getCardsInASubbox(\App\Models\Course $course, \App\Models\User $user, int $stage, int $sub_box)
 * @method static \Illuminate\Database\Eloquent\Collection<int, \App\Models\Card> listCardsInASubbox(\App\Models\Course $course, \App\Models\User $user, int $stage, int $sub_box)
 * @method static int|null countCompletedCards(\App\Models\Course $course, \App\Models\User $user)
 * @method static int|null countNotImportedCards(\App\Models\Course $course, \App\Models\User $user)
 *
 * @see \App\Services\Interfaces\TTS
 * @see \App\Services\Leitner
 */
class Leitner extends Facade
{
  /**
   * Get the registered name of the component.
   * 
   * This method returns the service container binding that the facade
   * will resolve to when methods are called on it.
   * 
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    // Returns the name of the service container binding for the Leitner service
    return 'leitner';
  }
}
