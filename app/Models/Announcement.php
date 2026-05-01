<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'section_id',   // null = all students, a value = specific class
        'title',
        'body',
        'pinned',       // show this at the top of the list
    ];

    protected $casts = [
        'pinned' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
