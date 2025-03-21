<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;


class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function handleRecordCreation(array $data): Teacher
    {
            
                // Create the User data
                $users = User::create([
                    'name' => $data['user']['name'],
                    'email' => $data['user']['email'],
                    'password' => $data['user']['password'],
                ]);

                // create the Teacher and link to the User
                $teacher = Teacher::create([
                    'user_id' => $users->id, 
                    'address' => $data['address'],
                ]);
                return $teacher;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
