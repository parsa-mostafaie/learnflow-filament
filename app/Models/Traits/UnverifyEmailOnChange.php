<?php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait UnverifyEmailOnChange
{
  public static function bootUnverifyEmailOnChange(): void
  {
    static::updating(function (Model $model) {
      if ($model->isDirty('email')) {
        $model->email_verified_at = null;
      }
    });
  }
}