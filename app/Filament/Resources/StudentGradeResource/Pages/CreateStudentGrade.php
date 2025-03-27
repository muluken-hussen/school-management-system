<?php

namespace App\Filament\Resources\StudentGradeResource\Pages;

use App\Filament\Resources\StudentGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Student_Subject;
use App\Models\Student_Grade;
use App\Models\Offer;
class CreateStudentGrade extends CreateRecord
{
    protected static string $resource = StudentGradeResource::class;

    protected function handleRecordCreation(array $data): Student_Grade
    {
        $gradeId = $data['grade_id'] ?? null;

        if (!$gradeId) {
            throw ValidationException::withMessages([
                'grade_id' => 'Grade ID is required.',
            ]);
        }

        // Fetch students who are not yet registered in this grade
        $unregisteredStudents = Student::whereNotIn('id', function ($query) use ($gradeId) {
            $query->select('student_id')->from('student_grade')->where('grade_id', $gradeId);
        })->pluck('id');

        if ($unregisteredStudents->isEmpty()) {
            throw ValidationException::withMessages([
                'students' => 'All students are already registered for this grade.',
            ]);
        }

        // Register unregistered students
        foreach ($unregisteredStudents as $studentId) {
            Student_Grade::create([
                'student_id' => $studentId,
                'grade_id' => $gradeId,
            ]);
        }
        // Register for subjects
       $subjectIds = Subject::where('grade_id', $gradeId)->pluck('id')->toArray();
       $offers = Offer::whereIn('subject_id', $subjectIds)->get();
       
       foreach ($unregisteredStudents as $studentId) {
        foreach ($offers as $offer) {
        Student_Subject::create([
            'student_id' => $studentId,
            'offer_id' => $offer->id,
        ]);
    }
    }

        return Student_Grade::first();
    }
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
