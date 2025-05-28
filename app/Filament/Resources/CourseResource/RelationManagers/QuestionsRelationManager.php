<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
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
        return $table
            ->recordTitleAttribute('question')
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label(__('questions.columns.question')) // Localized label
                    ->searchable(),

                Tables\Columns\TextColumn::make('answer')
                    ->label(__('questions.columns.answer')) // Localized label
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('questions.columns.author')) // Localized label
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('questions.columns.status')) // Localized label
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'approved' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => __('questions.statuses.pending'),    // Localized "Pending"
                        'approved' => __('questions.statuses.approved'),  // Localized "Approved"
                        'rejected' => __('questions.statuses.rejected'),  // Localized "Rejected"
                        default => __('questions.statuses.unknown'),      // Localized "Unknown"
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
}
