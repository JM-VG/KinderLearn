<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Activity = one specific task inside a module
 * Examples: Watch a video, Do a quiz, Match letters
 */
class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'section_id',
        'level_number',
        'title',
        'type',          // 'video', 'quiz', 'matching', 'drag_drop', 'coloring', 'tracing'
        'content',       // JSON with activity data (questions, answers, etc.)
        'file_path',     // uploaded file (PDF worksheet, audio, video)
        'opens_at',      // when the activity becomes available to students
        'deadline',      // optional due date
        'stars_reward',  // how many stars students earn for completing this
        'order',         // display order
        'is_active',
    ];

    protected $casts = [
        'content'   => 'array',   // automatically converts JSON to PHP array
        'is_active' => 'boolean',
        'opens_at'  => 'datetime',
        'deadline'  => 'datetime',
    ];

    // This activity belongs to one module
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // This activity has many student submissions
    public function submissions()
    {
        return $this->hasMany(ActivitySubmission::class);
    }
}
