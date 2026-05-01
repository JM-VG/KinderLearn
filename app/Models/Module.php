<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Module = a learning topic
 * Examples: Alphabet, Numbers, Colors, Shapes, Basic Words
 */
class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'description',
        'icon',
        'cover_image',  // path to uploaded cover photo (nullable)
        'color',
        'order',
        'is_active',
        'teacher_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // A module has many activities (games, quizzes, etc.)
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    // A module has many progress records (one per student)
    public function progresses()
    {
        return $this->hasMany(Progress::class);
    }

    // The teacher who created this module
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
