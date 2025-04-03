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

    $newRole = User::sanitizeRole($newRole) ?? $this->user->next_role_name;

    $previous_role = $this->user->role_name;

    // changeRole the user
    if ($this->user->setRole($newRole)) {
        $this->user->save();

        event(new UserRoleChanged($this->user, auth()->user(), $previous_role, $this->user->role_name));

        // Dispatch events to reload the users table and the single user view
        $this->dispatch('users-table-reload');
        $this->dispatch('user-single-reload', $this->user->id);
    }
};

$promote = fn() => $this->action($this->user->next_role_name);
$demote = fn() => $this->action($this->user->previous_role_name);
?>

{{-- Container for the changeRole button --}}
<div>
  <x-button-group>
    @can('changeRole', [$this->user, $this->user->next_role_name, true])
      <x-primary-button wire:click="promote" title="{{ __('Promote') }}" class="text-green-600">
        <i class="fas fa-arrow-up"></i>
      </x-primary-button>
    @endcan
    @can('changeRole', [$this->user, $this->user->previous_role_name, true])
      <x-danger-button wire:click="demote" title="{{ __('Demote') }}" class="text-red-600">
        <i class="fas fa-arrow-down"></i>
      </x-danger-button>
    @endcan
  </x-button-group>
</div>
