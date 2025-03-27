<?php

namespace App\Models;
use App\Models\Offer;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student_Subject extends Model
{
    protected $table = 'student_subject'; // Define the table name
    protected $fillable = ['student_id', 'offer_id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
