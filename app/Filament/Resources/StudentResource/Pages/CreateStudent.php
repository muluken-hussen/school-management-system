<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Student
    {
            
                // Create the User data
                $users = User::create([
                    'name' => $data['user']['name'],
                    'email' => $data['user']['email'],
                    'password' => $data['user']['password'],
                ]);

                // Then create the Student and link to the User
                $student = Student::create([
                    'user_id' => $users->id, // Foreign key to link user and student
                    'address' => $data['address'],
                    'roll_number' => $data['roll_number'],
                ]);
                return $student;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}