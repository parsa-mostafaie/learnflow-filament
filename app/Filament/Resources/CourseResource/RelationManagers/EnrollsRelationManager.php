<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Filament\Resources\UserResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use App\Filament\Resources\UserResource\Schemas\UsersTable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Course;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Facades\Leitner;

class EnrollsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrolls';

    public function form(Form $form): Form
    {
        return UserResource::form($form);
    }

    public function table(Table $table): Table
    {
        return UserResource::table($table)
            ->columns([
                ...UsersTable::columns(),
                Tables\Columns\TextColumn::make('last_course_visit')
                    ->label(__('courses.columns.last_course_visited'))
                    ->dateTime()
                    ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('learned_percentage')
                    ->label(__('courses.columns.learned_percentage'))
                    ->badge()
                    ->getStateUsing(function ($record) {
                        $owner = $this->getOwnerRecord();
                        $percentage = Leitner::getLearnedPercentage($owner, $record);

                        return $percentage;
                    })
                    ->formatStateUsing(fn($state) => Number::percentage($state, 0, 2))
                    ->color(function ($state) {
                        return match (true) {
                            $state >= 95 => 'primary',
                            $state >= 80 => 'success',
                            $state >= 60 => 'warning',
                            $state >= 30 => 'gray',
                            default => 'danger',
                        };
                    })
                    ->icon(function ($state) {
                        return match (true) {
                            $state >= 95 => 'heroicon-o-academic-cap',
                            $state >= 80 => 'heroicon-o-check-circle',
                            $state >= 60 => 'heroicon-o-clock',
                            $state >= 30 => 'heroicon-o-arrow-trending-up',
                            default => 'heroicon-o-x-circle',
                        };
                    })
                ,
            ])
            ->recordTitleAttribute('name')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->inverseRelationship('enrolledCourses');
    }

    public static function getTitle($ownerRecord, $pageClass): string
    {
        return __('users.plural'); // Change the plural title
    }

    public static function getModelLabel(): string
    {
        return __('users.singular'); // Change the singular title
    }

    public static function getPluralModelLabel(): string
    {
        return __('users.plural'); // Change the plural title
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return auth()->user()->can('seeEnrolls', $ownerRecord);
    }
}
