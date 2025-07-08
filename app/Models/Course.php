<?php

namespace App\Models;

use App\Models\Traits\HasImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Course
 * 
 * This model represents a course and provides functionality for managing courses.
 * A course has attributes like title, description, user ID, slug, and thumbnail.
 * It also includes traits for image handling, enrollments, author association, and daily tasks.
 */
class Course extends Model
{
    use HasFactory, LogsActivity, HasImage, Traits\Enrollable, Traits\HasAuthor, SoftDeletes, Traits\HasDailyTasks;

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

            if (!$user || !$user->can('view all courses')) {
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

    public function questions_all()
    {
        return $this->questions()->withoutGlobalScope('hide_not_approveds');
    }

    public function questions_approved()
    {
        return $this->questions_all()->ofStatus('approved');
    }

    public function questions_rejected()
    {
        return $this->questions_all()->ofStatus('rejected');
    }

    public function questions_pending()
    {
        return $this->questions_all()->ofStatus('pending');
    }

    /**
     * Search in courses
     */
    public function scopeSearch(Builder $builder, array $search): void
    {
        $_ = fn($key, $default = null) => data_get($search, $key, $default);

        $builder->when($_('text'), function ($query, $text) {
            $query->where(function ($builder) use ($text) {
                $searchText = "%{$text}%";
                $builder
                    ->where('title', 'like', $searchText)
                    ->orWhere('description', 'like', $searchText)
                    ->orWhere('slug', 'like', $searchText)
                    ->orWhereHas('user', function ($userQuery) use ($searchText) {
                        $userQuery->where('name', 'like', $searchText);
                    });
            });
        });

        $builder->when($_('filters.only_enrolled', false), function ($query) {
            $query->whereHas('enrolls', function ($enrollQuery) {
                $enrollQuery->where('user_id', auth()->id());
            });
        });

        $allowedSortColumns = [
            'courses.created_at',
            'title',
            'enrolls_count',
            'questions_approved_count',
        ];

        $sortBy = $_('sortBy', 'courses.created_at');
        if (!in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'courses.created_at';
        }

        $sortDirection = strtolower($_('sortDirection', 'desc')) === 'asc' ? 'asc' : 'desc';

        $builder->orderBy($sortBy, $sortDirection);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'description', 'slug', 'user_id', 'deleted_at', 'user.name'])
            ->useLogName('system')
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }

    public function activitySubjectStamp()
    {
        return view('components.activity-stamp', ['title' => $this->title, 'slug' => $this->slug, 'title_link' => route('course.single', $this->slug)]);
    }

    public function getLearnUrlAttribute()
    {
        return route('course.single', $this->slug);
    }

    public function getReportUrlAttribute()
    {
        return route('course.report', $this->id);
    }

    protected function approvedQuestionsCount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $key = config("app.name") . ".approved_questions_count_" . static::class . "_" . $this->id;

                if (request()->attributes->has($key)) {
                    return request()->attributes->get($key);
                }

                $count = array_key_exists('questions_approved_count', $this->getAttributes()) ? $this->getAttributes()['questions_approved_count'] : $this->questions_approved()->count();

                request()->attributes->set($key, $count);

                return $count;
            }
            // fn() => $this->questions_approved()->count()
        )->withoutObjectCaching();
    }

    protected function formattedApprovedQuestionsCount(): Attribute
    {
        return Attribute::make(
            get: fn() => forhumans($this->approved_questions_count)
        )->withoutObjectCaching();
    }
}
