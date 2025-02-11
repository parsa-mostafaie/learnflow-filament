<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseQuestion extends Model
{
  use HasFactory;

  protected $fillable = [
    'course_id',
    'question',
  ];

  public function course()
  {
    return $this->belongsTo(Course::class)->withTrashed();
  }

  public function question()
  {
    return $this->belongsTo(Question::class);
  }

  public function cards()
  {
    return $this->hasMany(Card::class);
  }
}
