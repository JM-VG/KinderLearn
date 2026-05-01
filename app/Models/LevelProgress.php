<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelProgress extends Model
{
    protected $fillable = [
        'user_id', 'module_id', 'level_number',
        'completed', 'stars_earned', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'completed'    => 'boolean',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function module() { return $this->belongsTo(Module::class); }
}
