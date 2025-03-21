<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    protected $fillable = [
        'grade_id',
        'subject_name',
];

     // Define the inverse relationship
     public function grade()
     {
         return $this->belongsTo(Grade::class);
     }
}
