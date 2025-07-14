<?php
namespace App\Filament\Resources\UserResource\Schemas;

use Filament\Tables;
use Filament\Support\Colors\Color;

class UsersTable
{
  public static function columns(): array
  {
    return [
      Tables\Columns\ImageColumn::make('filament_avatar_url')
        ->label(__('users.columns.avatar'))
        ->circular()
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
      Tables\Columns\TextColumn::make('email')
        ->label(__('users.columns.email'))
        ->color('primary')
        ->weight('bold')
        ->icon('heroicon-o-envelope')
        ->url(fn($record) => $record->email ? "mailto:{$record->email}" : null)
        ->searchable(),
      Tables\Columns\TextColumn::make('enrolled_courses_count')
        ->label(__('users.columns.enrolled_courses_count'))
        ->counts('enrolledCourses')
        ->numeric()
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: true),
      Tables\Columns\TextColumn::make('courses_count')
        ->label(__('users.columns.courses_count'))
        ->counts('courses')
        ->numeric()
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: true),
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
            default => Color::Lime,
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
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: true),
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
    ];
  }
}
