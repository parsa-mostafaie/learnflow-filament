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
    @can('changeRole', [$this->user_model])
      {{-- Render the change role button if the user has permission --}}
      <livewire:users.action.change-role-button :user="$this->user_model" />
    @endcan

    @canImpersonate($guard = null)
    @canBeImpersonated($this->user_model, $guard = null)
    <livewire:users.action.impersonate :user="$this->user_model" />
    @endCanImpersonate
    @endCanBeImpersonated
  </x-button-group>
</div>
