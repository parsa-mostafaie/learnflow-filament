@props(['status'])

{{-- TODO: Refactor to match Enum Structure -- Or remove this! --}}
@if ($status === 'pending')
  <span class="inline-flex items-center text-blue-500">
    <x-heroicon-s-clock class="ms-2 size-5" /> {{-- Pending Icon --}}
  </span>
@elseif ($status === 'approved')
  <span class="inline-flex items-center text-green-500">
    <x-heroicon-s-check-circle class="ms-2 size-5" /> {{-- Approved Icon --}}
  </span>
@elseif ($status === 'rejected')
  <span class="inline-flex items-center text-red-500">
    <x-heroicon-s-x-circle class="ms-2 size-5" /> {{-- Rejected Icon --}}
  </span>
@endif
