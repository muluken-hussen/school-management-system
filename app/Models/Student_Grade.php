<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Grade;
use App\Models\Student;

class Student_Grade extends Model
{
    protected $table = 'student_grade'; // Define the table name
    protected $fillable = ['student_id', 'grade_id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
