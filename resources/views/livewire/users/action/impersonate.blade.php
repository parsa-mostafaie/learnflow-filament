@props(['user'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\mount;
use App\Events\UserRoleChanged;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use App\Models\User;

// Define the state for the component
state(['user']);

/**
 * Function to handle the impersonate action
 */
$action = function () {
    $guardName = null;
    $manager = app('impersonate');

    // Cannot impersonate yourself
    if ($this->user->is(auth()->user())) {
        abort(403);
    }

    // Cannot impersonate again if you're already impersonate a user
    if ($manager->isImpersonating()) {
        abort(403);
    }

    if (!auth()->user()->canImpersonate()) {
        abort(403);
    }

    if ($this->user->canBeImpersonated()) {
        if ($manager->take(auth()->user(), $this->user, $guardName)) {
            $takeRedirect = $manager->getTakeRedirectTo();
            if ($takeRedirect !== 'back') {
                $this->redirect($takeRedirect, navigate: true);
            }
        }
    }
};
?>

{{-- Container for the changeRole button --}}
<div>
  @canImpersonate($guard = null)
    @canBeImpersonated($this->user, $guard = null)
      <x-primary-button wire:click="action" title="{{ __('Impersonate') }}" class="text-blue-600">
        <i class="fas fa-user-secret"></i>
      </x-primary-button>
    @endCanBeImpersonated
  @endCanImpersonate
</div>
