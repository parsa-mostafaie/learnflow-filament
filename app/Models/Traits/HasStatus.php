<?php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Enums\Status as StatusEnum;

trait HasStatus
{
  public static function toStatusEnum(StatusEnum|string $value): ?StatusEnum
  {
    return is_string($value) ? StatusEnum::tryFrom($value) : $value;
  }

  public static function toEnumValue(StatusEnum|string $value): string
  {
    return Str::lower($value instanceof StatusEnum ? $value->value : $value);
  }

  public function setStatus($newStatus)
  {
    $this->status = static::toStatusEnum($newStatus);

    return $this->save();
  }

  public function isStatus($status)
  {
    return static::toEnumValue($this->status) == static::toEnumValue($status);
  }

  #[Scope]
  protected function ofStatus(Builder $builder, string|StatusEnum|array $status): void
  {
    $builder->whereIn('questions.status', collect(Arr::wrap($status))
      ->map(fn($value) => static::toEnumValue($value))->filter());
  }
}