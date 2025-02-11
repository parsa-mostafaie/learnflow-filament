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
    use HasFactory, HasImage, Traits\Enrollable, Traits\HasAuthor, SoftDeletes, Traits\HasDailyTasks;

    protected $fillable = ['title', 'description', 'user_id', 'slug', 'thumbnail'];

    protected static function booted(): void
    {
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

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'course_questions');
    }
}
