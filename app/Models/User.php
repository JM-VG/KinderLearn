<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The fields that can be filled by forms.
     * This prevents unwanted data from being saved.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'bio',
        'pin',
        'section_id',
        'email_verification_code',
        'email_verification_expires_at',
    ];

    /**
     * These fields are hidden when converting to JSON.
     * This keeps passwords safe.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pin',
    ];

    /**
     * These fields are automatically cast to specific types.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // -----------------------------------------------
    // RELATIONSHIPS
    // These connect this model to other tables.
    // -----------------------------------------------

    // A student belongs to one class/section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // A teacher can have many classes
    public function sections()
    {
        return $this->hasMany(Section::class, 'teacher_id');
    }

    // A student has many progress records
    public function progresses()
    {
        return $this->hasMany(Progress::class);
    }

    // A user can have many achievements/badges
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    // A user can send and receive messages
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // Attendance records for a student
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Notifications for this user
    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    // -----------------------------------------------
    // HELPER METHODS
    // Easy ways to check what role a user has.
    // -----------------------------------------------

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    // Get total stars/points earned by this student
    public function getTotalStars()
    {
        return $this->progresses()->sum('stars_earned');
    }

    // Get count of completed modules
    public function getCompletedModules()
    {
        return $this->progresses()->where('completed', true)->count();
    }
}
