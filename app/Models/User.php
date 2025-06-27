<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Question;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * 
 * This model represents a user and provides functionality for managing users.
 * A user has attributes like name, email, and password. The user model also includes traits for roles and notifications.
 */
class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasAvatar
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Traits\HasRoles, CausesActivity, LogsActivity, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url'
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

    /**
     * Get the questions authored by the user.
     * 
     * This method defines the relationship between the user and their questions.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class); // A user can be an author of many questions
    }

    /**
     * Get the courses authored by the user.
     * 
     * This method defines the relationship between the user and their courses.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class); // A user can be an author of many courses
    }

    /**
     * Get the courses the user is enrolled in.
     * 
     * This method defines the relationship between the user and the courses they are enrolled in.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class)
            ->withTrashed()
            ->withPivot('last_course_visit')
            ->withTimestamps(); // A user can enroll in many courses
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'email_verified_at'])
            ->useLogName('system')
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['role', 'updated_at', 'remember_token', 'password']);
    }

    public function activitySubjectStamp()
    {
        return view('components.activity-stamp', ['title' => $this->name, 'slug' => $this->email, 'title_link' => ""]);
    }

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->can('impersonate users');
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        return !$this->can('prevent from impersonation by users');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');

        return $this->$avatarColumn
            ? Storage::disk('public')->url($this->$avatarColumn)
            : null;
    }

    public function getFilamentAvatarUrlAttribute(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');

        return $this->$avatarColumn
            ? Storage::disk('public')->url($this->$avatarColumn)
            : app(\Filament\Facades\Filament::getDefaultAvatarProvider())->get($this);
    }
}
