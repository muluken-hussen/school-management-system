<?php

namespace App\Filament\Resources\OfferResource\Pages;

use App\Filament\Resources\OfferResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Offer;
use App\Models\Subject;
use App\Models\Teacher;

class CreateOffer extends CreateRecord
{
    protected static string $resource = OfferResource::class;

    protected function handleRecordCreation(array $data): Offer
    {
        foreach ($data as $key => $teacherId) {
            if (strpos($key, 'teacher_id_') === 0 && !empty($teacherId)) {
                $subjectId = str_replace('teacher_id_', '', $key);
    
                // Check if the entry already exists to prevent duplicates
                $existingOffer = Offer::where('subject_id', $subjectId)
                    ->where('teacher_id', $teacherId)
                    ->exists();
    
                if (!$existingOffer) {
                    Offer::create([
                        'subject_id' => $subjectId,
                        'teacher_id' => $teacherId,
                    ]);
                }
            }
        }

        return $offers = Offer::first(); 
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
