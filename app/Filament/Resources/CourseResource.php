<?php

namespace App\Filament\Resources;

use Filament\Support\Enums\FontWeight;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use App\Filament\Resources\CourseResource\Pages;
use Illuminate\Support\Facades\Gate;
use App\Filament\Actions\LearnAction;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use App\Filament\Resources\CourseResource\RelationManagers;
use Filament\Tables\Columns\Summarizers\Range;
use App\Filament\Actions\EnrollToggleAction;
use App\Models\Course;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Filament\Resources\CourseResource\RelationManagers\QuestionsRelationManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Milwad\LaravelValidate\Rules\ValidSlug;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('courses.columns.title'))
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (?string $state, $set, $get, ?string $operation = null, ?string $old = null, ?Model $record = null) {
                        if ($operation == 'edit' /*&& $record->isPublished()*/) {
                            return;
                        }

                        if (($get('slug') ?? '') !== Str::slug($old ?? '')) {
                            return;
                        }

                        $set('slug', Str::slug($state ?? ''));
                    })
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->label(__('courses.columns.description'))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('slug')
                    ->label(__('courses.columns.slug'))
                    ->required()
                    ->maxLength(255)
                    ->rule(fn($record) => [new ValidSlug])
                    ->unique(ignoreRecord: true)
                    ->afterStateUpdated(function (Set $set) {
                        $set('is_slug_changed_manually', true);
                    }),
                Forms\Components\FileUpload::make('thumbnail')
                    ->label(__('courses.columns.thumbnail'))
                    ->image()
                    ->directory('course-thumbnails')
                    ->maxSize(1024)
                    ->previewable(),
                Forms\Components\Hidden::make('is_slug_changed_manually')
                    ->default(false)
                    ->dehydrated(false),
            ]);
    }


    public static function getRecordSubNavigation($page): array
    {
        return $page->generateNavigationItems([
            CourseResource\Pages\ViewCourse::class,
            CourseResource\Pages\EditCourse::class,
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label(__('courses.columns.thumbnail'))
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl((new Course)->getAlternativeImage())
                    ->size(40)   // Optional: Set size of the thumbnail
                ,
                Tables\Columns\TextColumn::make('id')
                    ->label(__('courses.columns.id'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('courses.columns.title'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('enrolls_count')
                    ->label(__('courses.columns.enrolls_count'))
                    ->counts('enrolls')
                    ->sortable(),
                Tables\Columns\TextColumn::make('questions_all_count')
                    ->label(__('courses.columns.all_questions_count'))
                    ->counts('questions_all')
                    ->sortable(),
                Tables\Columns\TextColumn::make('questions_approved_count')
                    ->label(__('courses.columns.approved_questions_count'))
                    ->counts('questions_approved')
                    ->sortable(),
                Tables\Columns\TextColumn::make('questions_rejected_count')
                    ->label(__('courses.columns.rejected_questions_count'))
                    ->counts('questions_rejected')
                    ->sortable(),
                Tables\Columns\TextColumn::make('questions_pending_count')
                    ->label(__('courses.columns.pending_questions_count'))
                    ->counts('questions_pending')
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('courses.columns.slug'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label(__('courses.columns.author'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('courses.columns.deleted_at'))
                    ->dateTime()
                    ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('courses.columns.created_at'))
                    ->dateTime()
                    ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('courses.columns.updated_at'))
                    ->dateTime()
                    ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('author')
                    ->label(__('courses.filters.author'))
                    ->relationship('author', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload(),
                // Date Range Filter for "Creation Range"
                DateRangeFilter::make('created_at')
                    ->label(__('courses.filters.creation_range'))
                    ->firstDayOfWeek(6)
                    ->autoApply()
                    ->linkedCalendars(),
                // Date Range Filter for "Updation Range"
                DateRangeFilter::make('updated_at')
                    ->label(__('courses.filters.updation_range'))
                    ->firstDayOfWeek(6)
                    ->autoApply()
                    ->linkedCalendars(),

                // Date Range Filter for "Deletion Range"
                DateRangeFilter::make('deletion_range')
                    ->label(__('courses.filters.deletion_range'))
                    ->firstDayOfWeek(6)
                    ->autoApply()
                    ->linkedCalendars()
            ])
            ->filtersTriggerAction(
                fn(ActionsAction $action) => $action
                    ->button()
                    ->label(__('tables.filter')),
            )
            ->actions([
                Tables\Actions\ViewAction::make(),
                LearnAction::make(),
                EnrollToggleAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                ActivityLogTimelineTableAction::make('Activities')
                    ->authorize(fn() => auth()->user()->can('manage any activities'))
                    ->label(__('tables.actions.activities')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ActivitylogRelationManager::class,
            QuestionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'view' => Pages\ViewCourse::route('/{record}'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('courses.singular'); // Change the singular title
    }

    public static function getPluralModelLabel(): string
    {
        return __('courses.plural'); // Change the plural title
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->withCount('enrolls');
    }

    public static function getWidgets(): array
    {
        return [CourseResource\Widgets\CourseOverview::class];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(__('courses.sections.main_info'))
                    ->description(__('courses.sections.main_info_desc'))
                    ->columns(1)
                    ->schema([
                        ImageEntry::make('thumbnail')
                            ->label(false)
                            ->disk('public')
                            ->defaultImageUrl((new Course)->getAlternativeImage())
                            ->extraImgAttributes(['loading' => 'lazy', 'class' => 'rounded']),
                        Group::make()
                            ->schema([
                                TextEntry::make('title')
                                    ->label(false)
                                    ->color('primary')
                                    ->weight('bold')
                                    ->placeholder(__('courses.placeholders.title'))
                                    ->size('lg'),
                                Grid::make()
                                    ->columns(3)
                                    ->schema([
                                        TextEntry::make('slug')
                                            ->label(__('courses.columns.slug'))
                                            ->copyable()
                                            ->color('gray')
                                            ->placeholder(__('courses.placeholders.slug'))
                                            ->icon('heroicon-o-link'),
                                        TextEntry::make('author.name')
                                            ->label(__('courses.columns.author'))
                                            ->weight('bold')
                                            ->color('info'),
                                        TextEntry::make('enrolls_count')
                                            ->label(__('courses.columns.enrolls_count'))
                                            ->color('success')
                                            ->weight('bold')
                                            ->numeric(),
                                    ]),

                            ])
                            ->columnSpan(1),
                    ]),
                Section::make(__('courses.sections.details'))
                    ->columns(1)
                    ->schema([
                        TextEntry::make('description')
                            ->label(false)
                            ->placeholder(__('courses.placeholders.description'))
                            ->markdown(),
                    ]),
                Section::make(__('courses.sections.meta'))
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('courses.columns.created_at'))
                            ->dateTime()
                            ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                            ->color('success')
                            ->placeholder(__('courses.placeholders.created_at')),

                        TextEntry::make('updated_at')
                            ->label(__('courses.columns.updated_at'))
                            ->dateTime()
                            ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                            ->color('warning')
                            ->placeholder(__('courses.placeholders.updated_at')),

                        TextEntry::make('deleted_at')
                            ->label(__('courses.columns.deleted_at'))
                            ->dateTime()
                            ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                            ->color('danger')
                            ->placeholder(__('courses.placeholders.deleted_at')),
                    ]),
            ]);
    }

}
