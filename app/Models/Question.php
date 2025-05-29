<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Question
 * 
 * This model represents a question and provides functionality for managing questions.
 * A question has attributes like the question text and the answer.
 */
class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory, Traits\HasAuthor, LogsActivity;

    protected $fillable = ["question", "answer", 'user_id', 'status'];

    /**
     * Get the courses that the question is associated with.
     * 
     * This method defines the relationship between the question and its courses.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_questions');
    }

    /**
     * Search for questions based on the incoming criteria.
     * Now supports text search, status, user ownership, and assignment filters.
     *
     * @param  Builder  $builder
     * @param  array  $search
     * @return void
     */
    public function scopeSearch(Builder $builder, array $search): void
    {
        // Helper to safely retrieve values from the search array.
        $_ = fn($key, $default = null) => data_get($search, $key, $default);

        // Text search across question and answer fields.
        $builder->when($_('text'), function ($query, $text) {
            $query->where(DB::raw("CONCAT(questions.question, ': ', questions.answer)"), 'like', "%{$text}%");
        })
            // Filter by status.
            ->when($_('filters.status'), function ($query, $status) {
                $query->where('questions.status', $status);
            })
            // Filter by user ownership.
            ->when($_('filters.only_my_questions', false), function ($query) {
                $query->where('questions.user_id', auth()->id());
            })
            // Filter by assignment state; expects "filters.assignment" with a value of 'assigned' or 'unassigned'
            ->when($_('filters.assignment'), function ($query, $assignment) use ($search) {
                $courseId = data_get($search, 'course_id');
                if ($courseId) {
                    if ($assignment === 'assigned') {
                        $query->whereIn('questions.id', function ($q) use ($courseId) {
                            $q->select('course_questions.question_id')
                                ->from('course_questions')
                                ->where('course_questions.course_id', $courseId);
                        });
                    } elseif ($assignment === 'unassigned') {
                        $query->whereNotIn('questions.id', function ($q) use ($courseId) {
                            $q->select('course_questions.question_id')
                                ->from('course_questions')
                                ->where('course_questions.course_id', $courseId);
                        });
                    }
                }
            });

        // Whitelist for allowed sort columns.
        $allowedSortColumns = [
            'questions.created_at',
            'questions.status',
            'questions.question',
        ];

        $sortBy = $_('sortBy', 'questions.created_at');
        if (!in_array($sortBy, $allowedSortColumns, true)) {
            $sortBy = 'questions.created_at';
        }

        $sortDirection = strtolower($_('sortDirection', 'desc')) === 'asc' ? 'asc' : 'desc';

        $builder->orderBy($sortBy, $sortDirection);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['question', 'answer', 'user_id', 'user.name', 'status'])
            ->useLogName('system')
            ->logOnlyDirty();
    }

    public function activitySubjectStamp()
    {
        return view('components.activity-stamp', ['title' => $this->question, 'slug' => $this->answer]);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('hide_not_approveds', function (Builder $builder) {
            $user = Auth::user();

            if (!$user || !$user->can('view all questions')) {
                $builder->where(function ($query) use ($user) {
                    $query->where('status', 'approved')
                        ->orWhere('questions.user_id', $user?->id);
                });
            }
        });
    }

    public function setStatus($newStatus)
    {
        $this->status = $newStatus;

        return $this->save();
    }

    public function isStatus($status)
    {
        return Str::lower($this->status) == Str::lower($status);
    }
}
