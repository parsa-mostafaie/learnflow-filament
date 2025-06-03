<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use App\Filament\Imports\QuestionImporter;
use App\Filament\Resources\QuestionResource;
use Filament\Tables\Table;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question')
                    ->label(__('questions.columns.question')) // Localized label
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('answer')
                    ->label(__('questions.columns.answer')) // Localized label
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return QuestionResource::table($table)
            ->recordTitleAttribute('question')
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                ImportAction::make()
                    ->importer(QuestionImporter::class)
                    ->options(['course_id' => $this->getOwnerRecord()->getKey()]),
                Tables\Actions\AttachAction::make()->preloadRecordSelect()
                    ->recordSelectSearchColumns(['question', 'answer'])
                    ->multiple()
                    ->authorize(fn() => auth()->user()->can('attachAnyQuestion', $this->getOwnerRecord())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make()
                    ->authorize(fn() => auth()->user()->can('detachQuestion', $this->getOwnerRecord())),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\DetachBulkAction::make()
                        ->authorize(fn() => auth()->user()->can('detachAnyQuestion', $this->getOwnerRecord())),
                ]),
            ]);
    }

    public static function getTitle($ownerRecord, $pageClass): string
    {
        return __('questions.plural'); // Change the plural title
    }

    public static function getModelLabel(): string
    {
        return __('questions.singular'); // Change the singular title
    }

    public static function getPluralModelLabel(): string
    {
        return __('questions.plural'); // Change the plural title
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-question-mark-circle';
    }
}
