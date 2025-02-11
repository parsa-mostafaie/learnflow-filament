<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Question;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Traits\HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Accessor for role name
    public function getRoleNameAttribute()
    {
        return array_flip(static::roles)[$this->role];
    }

    public function questions()
    {
        return $this->hasMany(Question::class); // A user can be an author of many questions
    }

    // Relation to Course model
    public function courses()
    {
        return $this->hasMany(Course::class); // A user can be an author of many courses
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class)->withTrashed()->withPivot('last_course_visit')->withTimestamps(); // A user can enroll in many courses
    }
}
