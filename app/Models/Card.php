<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Card
 * 
 * This model represents a card, which is associated with a user and a course question. 
 * The card has attributes like user ID, course question ID, review date, and stage.
 */
class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_question_id',
        'review_date',
        'stage',
    ];

    /**
     * Get the user that owns the card.
     * 
     * This method defines the relationship between the card and the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course question that the card is associated with.
     * 
     * This method defines the relationship between the card and the course question.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courseQuestion()
    {
        return $this->belongsTo(CourseQuestion::class);
    }
}
