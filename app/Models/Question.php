<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    protected $fillable = ["question", "answer", 'user_id'];

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
     * Search for questions based on a search term.
     * 
     * This method searches for questions where the concatenation of the question and answer matches the search term.
     * 
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function search($search)
    {
        return Question::where(DB::raw('CONCAT(question,  ": ", answer)'), 'like', '%' . $search . '%');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['question', 'answer', 'user_id', 'user.name'])
            ->useLogName('system')
            ->logOnlyDirty();
    }

    public function activitySubjectStamp()
    {
        return view('components.activity-stamp', ['title' => $this->question, 'slug' => $this->answer]);
    }
}
