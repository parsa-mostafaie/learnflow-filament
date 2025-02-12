<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CourseQuestion
 * 
 * This model represents a course question and provides functionality for managing questions within a course.
 * A course question has attributes like course ID and question.
 */
class CourseQuestion extends Model
{
  use HasFactory;

  protected $fillable = [
    'course_id',
    'question',
  ];

  /**
   * Get the course that the question is associated with.
   * 
   * This method defines the relationship between the course question and the course, 
   * including soft-deleted courses.
   * 
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function course()
  {
    return $this->belongsTo(Course::class)->withTrashed();
  }

  /**
   * Get the question associated with the course question.
   * 
   * This method defines the relationship between the course question and the question.
   * 
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function question()
  {
    return $this->belongsTo(Question::class);
  }

  /**
   * Get the cards associated with the course question.
   * 
   * This method defines the relationship between the course question and its cards.
   * 
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }
}
