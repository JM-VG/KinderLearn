<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Progress = tracks how a student is doing in each module
 */
class Progress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',         // which student
        'module_id',       // which module
        'completed',       // has the student finished?
        'stars_earned',    // total stars from this module
        'time_spent',      // total seconds spent (for analytics)
        'last_activity_at',
    ];

    protected $casts = [
        'completed'        => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $table = 'progresses';
    
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
