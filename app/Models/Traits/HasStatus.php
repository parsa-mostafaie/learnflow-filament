<?php
namespace App\Models\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HasStatus
{
  public function setStatus($newStatus)
  {
    $this->status = $newStatus;

    return $this->save();
  }

  public function isStatus($status)
  {
    return Str::lower($this->status) == Str::lower($status);
  }

  public function scopeStatus(Builder $builder, string $status): void
  {
    $builder->whereIn('status', Arr::wrap($status));
  }
}