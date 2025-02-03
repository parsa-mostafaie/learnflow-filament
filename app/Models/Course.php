<?php

namespace App\Models;

use App\Models\Traits\HasImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasFactory, HasImage, Traits\Enrollable, Traits\HasAuthor, SoftDeletes;

    protected $fillable = ['title', 'description', 'user_id', 'slug', 'thumbnail'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        /** Hide Soft-Deleted models that current user (if not a developer) is not author of them */
        static::addGlobalScope('hide_deleteds', function (Builder $builder) {
            $user = Auth::user();

            if ($user && !isRole('developer')) {
                $builder->where(function ($query) use ($user) {
                    $query->whereNull('deleted_at')
                        ->orWhere('user_id', $user->id);
                });
            }
        });
    }
}
