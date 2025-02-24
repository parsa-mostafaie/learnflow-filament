<?php
namespace App\Livewire\Traits;

trait TableCustomizations
{
  function getFilterAttributes()
  {
    return ['inactiveColor' => 'bg-neutral-200', 'activeColor' => 'bg-purple-600', 'default-styling' => true, 'blobColor' => 'bg-gray-100'];
  }
}