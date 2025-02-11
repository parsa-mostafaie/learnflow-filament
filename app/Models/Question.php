<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory, Traits\HasAuthor;

    protected $fillable = ["question", "answer"];


    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_questions');
    }

    public static function search($search){
        return Question::where(DB::raw('CONCAT(question,  ": ", answer)'), 'like', '%' . $search . '%');
    }
}
