<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Achievement = a badge or reward a student has earned
 * Examples: "Alphabet Master", "Number Wizard", "Perfect Score"
 */
class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',       // e.g., "Alphabet Master"
        'description', // e.g., "Completed all alphabet activities!"
        'icon',        // emoji or image filename
        'type',        // 'badge', 'milestone', 'perfect_score'
        'earned_at',
    ];

    protected $casts = [
        'earned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
