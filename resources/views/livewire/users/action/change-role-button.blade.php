@props(['user'])

<?php
use function Livewire\Volt\state;
use function Livewire\Volt\computed;
use App\Events\UserRoleChanged;
use App\Events\{CourseEnrollment, CourseUnenrollment};
use App\Models\User;

// Define the state for the component
state(['user']);

/**
 * Function to handle the changeRole action
 */
$action = function ($newRole) {
    // Authorize the user to changeRole the user
    $this->authorize('changeRole', [$this->user, $newRole]);

    $previous_role = $this->user->role;

    // changeRole the user
    $this->user->role = User::validateRole($newRole);
    $this->user->save();

    event(new UserRoleChanged($this->user, auth()->user(), $previous_role, $this->user->role));

    // Dispatch events to reload the users table and the single user view
    $this->dispatch('users-table-reload');
    $this->dispatch('user-single-reload', $this->user->id);
};

$promote = fn() => $this->action($this->user->role + 1);
$demote = fn() => $this->action($this->user->role - 1);
?>

{{-- Container for the changeRole button --}}
<div>
  @can('changeRole', [$this->user, $this->user->role + 1, true])
    <x-primary-button wire:click="promote">{{ __('Promote') }}</x-primary-button>
  @endcan
  @can('changeRole', [$this->user, $this->user->role - 1, true])
    <x-danger-button wire:click="demote">{{ __('Demote') }}</x-danger-button>
  @endcan
</div>
