<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * UserNotification = bell notification for a user
 * Examples: "You earned a badge!", "New activity assigned!"
 */
class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',     // 'achievement', 'message', 'announcement', 'activity'
        'link',     // URL to go to when clicked
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isRead()
    {
        return $this->read_at !== null;
    }
}
