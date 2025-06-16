<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
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
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label(__('users.columns.avatar'))
                    ->disk('public')
                    ->circular()
                    /// TODO: Uncomment line below
                    // ->defaultImageUrl((new Course)->getAlternativeImage())
                    ->size(40),
                Tables\Columns\TextColumn::make('id')
                    ->label(__('users.columns.id'))
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('users.columns.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email') /// TODO: make this a link
                    ->label(__('users.columns.email'))
                    ->color('primary')
                    ->weight('bold')
                    ->icon('heroicon-o-envelope')
                    ->url(fn($record) => $record->email ? "mailto:{$record->email}" : null)
                    ->searchable(),
                /// TODO: for role, course count, enrolled courses count
                /// TODO: Colorized Columns,
                Tables\Columns\TextColumn::make('role_name')
                    ->label(__('users.columns.role'))
                    ->getStateUsing(fn($record) => $record->role_name)
                    ->badge()
                    ->color(
                        fn($state) => match ($state) {
                            'user' => 'gray',
                            'instructor' => 'info',
                            'manager' => 'primary',
                            'admin' => 'success',
                            default => 'success',
                        }
                    )
                    ->icon(
                        fn($state) => match ($state) {
                            'user' => 'heroicon-o-user',
                            'instructor' => 'heroicon-o-academic-cap',
                            'manager' => 'heroicon-o-shield-check',
                            'admin' => 'heroicon-o-cog',
                            default => 'heroicon-o-question-mark-circle',
                        }
                    )
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'user' => __('users.roles.user'),         // Localized "User"
                        'instructor' => __('users.roles.instructor'), // Localized "Instructor"
                        'manager' => __('users.roles.manager'),   // Localized "Manager"
                        'admin' => __('users.roles.admin'),       // Localized "Admin"
                        'developer' => __('users.roles.developer'),       // Localized "Developer"
                        default => __('users.roles.unknown'),     // Localized "Unknown"
                    }),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label(__('users.columns.email_verified_at'))
                    ->dateTime()
                    ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('users.columns.created_at'))
                    ->dateTime()
                    ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('users.columns.updated_at'))
                    ->dateTime()
                    ->when(\is_jalali_supported(), fn($column) => $column->jalaliDateTime('l j F Y H:i:s'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                /// TODO: Filters
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
        ];
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
