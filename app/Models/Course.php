<?php

namespace App\Models;

use App\Models\Traits\HasImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * Class Course
 * 
 * This model represents a course and provides functionality for managing courses.
 * A course has attributes like title, description, user ID, slug, and thumbnail.
 * It also includes traits for image handling, enrollments, author association, and daily tasks.
 */
class Course extends Model
{
    use HasFactory, HasImage, Traits\Enrollable, Traits\HasAuthor, SoftDeletes, Traits\HasDailyTasks;

    protected $fillable = ['title', 'description', 'user_id', 'slug', 'thumbnail'];

    /**
     * The "booted" method of the model.
     * 
     * This method is called when the model is booted. It sets up a global scope to hide deleted courses,
     * except for the courses owned by the authenticated user or developers.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope('hide_deleteds', function (Builder $builder) {
            $user = Auth::user();

            if (!$user || !isRole('developer')) {
                $builder->where(function ($query) use ($user) {
                    $query->whereNull('courses.deleted_at')
                        ->orWhere('courses.user_id', $user?->id);
                });
            }
        });
    }

    /**
     * Get the questions associated with the course.
     * 
     * This method defines the relationship between the course and its questions.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'course_questions');
    }

    /**
     * Search in courses
     */
    public function scopeSearch(Builder $builder, array $search): void
    {
        $_ = fn($address, $default = '') => data_get($search, $address, $default);
        $builder
            ->where(function ($builder) use ($_) {
                $search_text = "%{$_('search')}%";
                $builder
                    ->whereLike('title', $search_text)
                    ->orWhereLike('description', $search_text)
                    ->orWhereLike('slug', $search_text);
                $builder->orWhereHas('user', fn($user) => $user->whereLike('users.name', $search_text));
            })
            ->when($_('filters.only_enrolled', false), function ($builder) {
                $builder->whereHas('enrolls', function ($users) {
                    $users->where('users.id', auth()->id());
                });
            })
            ->orderBy($_('sortBy', 'created_at'), 'desc');
    }
}
