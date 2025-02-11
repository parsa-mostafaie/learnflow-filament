<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_question_id',
        'review_date',
        'stage',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courseQuestion()
    {
        return $this->belongsTo(CourseQuestion::class);
    }
}
