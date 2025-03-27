<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Filament\Resources\AssessmentResource\RelationManagers;
use App\Models\Assessment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Teacher;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentSubject;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateTimeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\BelongsToColumn;
use Filament\Tables\Columns\NumberColumn;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Redirect;
use App\Models\Student_Subject;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $userid = auth()->id();
        $teacher = Teacher::where('user_id', $userid)->first();

        if ($teacher == null) {
            Notification::make()
                ->title('Error')
                ->body('You are not assigned as a teacher. Please contact the admin.')
                ->danger()
                ->send();

            return $form->schema([
                Grid::make(2)->schema([
                    TextInput::make('student_name')->label('Student Name')->required(),
                    
                    Select::make('subject')
                        ->label('Subject')
                        ->options([]) // No subjects available
                        ->placeholder('No subjects available')
                        ->required(),

                    Textarea::make('assessment')->label('Assessment Notes')->required(),
                    TextInput::make('grade')->label('Grade')->required(),
                    DatePicker::make('assessment_date')->label('Assessment Date')->required(),
                ])
            ]);
        }

        $Offers_id = Offer::where('teacher_id', $teacher->id)->pluck('id')->toArray();
        $student_subjects = Student_Subject::whereIn('offer_id', $Offers_id)->get();

        // FIX: Prevent NULL labels
        $subjectOptions = $student_subjects
            ->mapWithKeys(fn($subject) => [$subject->id => $subject->offer->subject->subject_name ?? 'Unnamed Subject'])
            ->toArray();
/*             $student_subject_id = [];
            foreach ($student_subjects as $subject) {
                $student_subject_id[] = Select::make("student_subject_id_{$student_subjects->id}")
                    ->label("Assessment to {$subject->subject_name}")
                    ->options(Teacher::all()->pluck('user.name', 'id'))
                    ->required()
                    ->rules([
                        'required',
                        'exists:teachers,id'
                    ]);                    
            }
 */
        return $form->schema([
            Grid::make(2)->schema([
                TextInput::make('student_name')->label('Student Name')->required(),

                Select::make('subject')
                    ->label('Subject')
                    ->options($subjectOptions)
                    ->required()
                    ->placeholder('Select a subject'),

                Textarea::make('assessment')->label('Assessment Notes')->required(),
                TextInput::make('grade')->label('Grade')->required(),
                DatePicker::make('assessment_date')->label('Assessment Date')->required(),
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssessments::route('/'),
            'create' => Pages\CreateAssessment::route('/create'),
            'edit' => Pages\EditAssessment::route('/{record}/edit'),
        ];
    }
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
        ];
    }
}
