<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ActivitySubmission = one student's answer or uploaded work for one activity
 */
class ActivitySubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'user_id',
        'score',          // score out of 100
        'stars_earned',
        'answers',        // JSON with the student's answers
        'file_path',      // uploaded file (drawing, worksheet photo, etc.)
        'feedback',       // teacher's written feedback
        'completed_at',
    ];

    protected $casts = [
        'answers'      => 'array',
        'completed_at' => 'datetime',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
