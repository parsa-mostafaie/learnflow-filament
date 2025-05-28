<?php
namespace App\Filament\Resources;

use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Range;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle'; // Icon suggestion for questions

    public static function form(Form $form): Form
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

    public static function table(Table $table): Table
    {
        return $table
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('questions.columns.created_at')) // Localized label
                    ->dateTime()
                    ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                    ->sortable()
                    ->summarize(Range::make()->minimalDateTimeDifference())
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('questions.columns.updated_at')) // Localized label
                    ->dateTime()
                    ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                    ->sortable()
                    ->summarize(Range::make()->minimalDateTimeDifference())
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('author')
                    ->label(__('questions.filters.author'))
                    ->relationship('author', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),
                SelectFilter::make('status')
                    ->options([
                        'pending' => __('questions.statuses.pending'),
                        'approved' => __('questions.statuses.approved'),
                        'rejected' => __('questions.statuses.rejected'),
                    ])
                    ->label(__('questions.filters.status')) // Localized label
                    ->multiple(),
                // Date Range Filter for "Creation Range"
                DateRangeFilter::make('created_at')
                    ->label(__('questions.filters.creation_range'))
                    ->firstDayOfWeek(6)
                    ->autoApply()
                    ->linkedCalendars(),
                // Date Range Filter for "Updation Range"
                DateRangeFilter::make('updated_at')
                    ->label(__('questions.filters.updation_range'))
                    ->firstDayOfWeek(6)
                    ->autoApply()
                    ->linkedCalendars(),
            ])
            ->filtersTriggerAction(
                fn(ActionsAction $action) => $action
                    ->button()
                    ->label(__('tables.filter')),
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Tables\Actions\ForceDeleteAction::make(),
                // Tables\Actions\RestoreAction::make(),
                ActivityLogTimelineTableAction::make('Activities')
                    ->label(__('tables.actions.activities'))
                    ->authorize(fn() => auth()->user()->can('manage any activities')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\ForceDeleteBulkAction::make(),
                    //Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActivitylogRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'view' => Pages\ViewQuestion::route('/{record}'),
            'edit' => Pages\EditQuestion::route('/{record}/edit')
        ];
    }


    public static function getModelLabel(): string
    {
        return __('questions.singular'); // Change the singular title
    }

    public static function getPluralModelLabel(): string
    {
        return __('questions.plural'); // Change the plural title
    }

    public static function getRecordSubNavigation($page): array
    {
        return $page->generateNavigationItems([
            QuestionResource\Pages\ViewQuestion::class,
            QuestionResource\Pages\EditQuestion::class,
        ]);
    }
}
