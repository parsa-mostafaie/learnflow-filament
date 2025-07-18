<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;
use App\Models\User;
use App\Filament\Actions\ChangeRoleUserAction;
use App\Filament\Actions\ChangeRoleInstructorAction;
use App\Filament\Actions\ChangeRoleManagerAction;
use App\Filament\Actions\ChangeRoleAdminAction;
use App\Filament\Actions\ChangeRoleDeveloperAction;
use Filament\Forms;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Forms\Form;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Colors\Color;
use App\Filament\Resources\UserResource\Schemas\UsersTable;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('users.columns.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->label(__('users.columns.email'))
                    ->required()
                    ->maxLength(255),
                // Forms\Components\DateTimePicker::make('email_verified_at'),
                // Forms\Components\TextInput::make('password')
                //     ->password()
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('custom_fields'),
                // Forms\Components\TextInput::make('avatar_url')
                //     ->maxLength(255),
            ]);
    }

    public static function getRecordSubNavigation($page): array
    {
        return $page->generateNavigationItems([
            UserResource\Pages\ViewUser::class,
            // UserResource\Pages\EditUser::class,
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(UsersTable::columns())
            ->filters([
                SelectFilter::make('role')
                    ->label(__('users.filters.role'))
                    ->options(function () {
                        return User::getRoleNamesCollection()
                            ->mapWithKeys(
                                fn($value) => [
                                    $value => match ($value) {
                                        'user' => __('users.roles.user'),         // Localized "User"
                                        'instructor' => __('users.roles.instructor'), // Localized "Instructor"
                                        'manager' => __('users.roles.manager'),   // Localized "Manager"
                                        'admin' => __('users.roles.admin'),       // Localized "Admin"
                                        'developer' => __('users.roles.developer'),       // Localized "Developer"
                                        default => __('users.roles.unknown'),     // Localized "Unknown"
                                    }
                                ]
                            )
                            ->toArray();
                    })
                    ->query(function (Builder $query, $state) {
                        $value = $state['values'] ?? [];

                        if ($value)
                            $query->whereHas('roles', function (Builder $query) use ($value) {
                                $query->whereIn('name', Arr::wrap($value));
                            });
                    })
                    ->multiple(),
                SelectFilter::make('verify_state')
                    ->options([
                        'unverified' => __('users.filters.email_state.unverified'),
                        'verified' => __('users.filters.email_state.verified'),
                    ])
                    ->query(function (Builder $query, $state) {
                        $value = $state['value'] ?? null;

                        if ($value) {
                            if ($value == 'verified')
                                $query->whereNotNull('email_verified_at');
                            else
                                $query->whereNull('email_verified_at');
                        }
                    })
                    ->label(__('users.filters.email_state.label')),
                DateRangeFilter::make('email_verified_at')
                    ->label(__('users.filters.email_verified_range'))
                    ->firstDayOfWeek(6)
                    ->autoApply()
                    ->linkedCalendars(),
                // Date Range Filter for "Creation Range"
                DateRangeFilter::make('created_at')
                    ->label(__('users.filters.creation_range'))
                    ->firstDayOfWeek(6)
                    ->autoApply()
                    ->linkedCalendars(),
                // Date Range Filter for "Updation Range"
                DateRangeFilter::make('updated_at')
                    ->label(__('users.filters.updation_range'))
                    ->firstDayOfWeek(6)
                    ->autoApply()
                    ->linkedCalendars(),
                /// TODO: Filters
            ])
            ->filtersTriggerAction(
                fn(ActionsAction $action) => $action
                    ->button()
                    ->label(__('tables.filter')),
            )
            ->actions([
                ChangeRoleUserAction::make(),
                ChangeRoleInstructorAction::make(),
                ChangeRoleManagerAction::make(),
                ChangeRoleAdminAction::make(),
                ChangeRoleDeveloperAction::make(),
                Impersonate::make(),
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                ActivityLogTimelineTableAction::make('Activities')
                    ->label(__('tables.actions.activities'))
                    ->authorize(fn() => auth()->user()->can('manage any activities'))
                /// TODO: Implement Actions ++ Impersonate
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    /// TODO: Implement Bulk Actions
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // TODO: Relations
            ActivitylogRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('courses', 'enrolledCourses')
            ->with('roles');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('users.singular'); // Change the singular title
    }

    public static function getPluralModelLabel(): string
    {
        return __('users.plural'); // Change the plural title
    }

    /// TODO: Infolist
    /// TODO: Widgets: each role count
}
