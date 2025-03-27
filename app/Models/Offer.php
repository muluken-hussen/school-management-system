<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Subject;
use App\Models\Teacher;

class Offer extends Model
{
    protected $fillable = [
        'teacher_id',
        'subject_id',
    ];

        // Define the inverse relationship
        public function subject()
        {
            return $this->belongsTo(Subject::class);
        }
            // Define the inverse relationship
     public function teacher()
     {
         return $this->belongsTo(Teacher::class);
     }
}
