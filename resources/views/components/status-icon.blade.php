@props(['status'])

@if ($status === 'pending')
  <span class="inline-flex items-center text-blue-500">
    <i class="fas fa-clock ms-2"></i> {{-- Pending Icon --}}
  </span>
@elseif ($status === 'approved')
  <span class="inline-flex items-center text-green-500">
    <i class="fas fa-check-circle ms-2"></i> {{-- Approved Icon --}}
  </span>
@elseif ($status === 'rejected')
  <span class="inline-flex items-center text-red-500">
    <i class="fas fa-times-circle ms-2"></i> {{-- Rejected Icon --}}
  </span>
@endif
