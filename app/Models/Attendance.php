<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Attendance = daily attendance record for one student
 */
class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',     // which student
        'section_id',  // which class
        'date',
        'status',      // 'present', 'absent', 'late'
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
