<?php
// =====================================================
// Section (Class) Model
// A section is a group of students taught by one teacher
// =====================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',       // e.g., "Sunflower Class"
        'teacher_id', // which teacher owns this class
        'join_code',  // unique 6-letter code students use to join
        'description',
    ];

    // This class belongs to one teacher
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // This class has many students
    public function students()
    {
        return $this->hasMany(User::class);
    }

    // This class has many attendance records
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
