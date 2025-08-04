@props(['id'])

<?php
use function Livewire\Volt\state;

// Define the state for the component
state(['course']);
?>

<div>
  @can('getReport', $this->course)
    <a target="_blank" href="{{ $this->course->report_url }}" title="{{ __('report-page.action') }}">
      <x-secondary-button>
        <x-heroicon-s-chart-bar class="w-4 h-4" />
      </x-secondary-button>
    </a>
  @endcan
</div>
