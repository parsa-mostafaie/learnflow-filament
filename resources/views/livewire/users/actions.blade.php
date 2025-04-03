@props(['user'])

<?php
use function Livewire\Volt\{computed, state, on};
use App\Models\User;

// Define the state for the component
state(['user', 'in_show']);

// Define a computed property to get the user model
$user_model = computed(fn() => $this->user instanceof App\Models\User ? $this->user : User::findOrFail($this->user));
?>

{{-- Container for the action buttons --}}
<div>
  <x-button-group>
    @can('changeRole', [$this->user_model, $this->user_model->previous_role_name])
      {{-- Render the change role button if the user has permission --}}
      <livewire:users.action.change-role-button :user="$this->user_model" />
    @endcan

    @can('impersonate', $this->user_model)
      <livewire:users.action.impersonate :user="$this->user_model" />
    @endcan
  </x-button-group>
</div>
