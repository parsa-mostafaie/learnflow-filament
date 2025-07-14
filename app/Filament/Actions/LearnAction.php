<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;

class LearnAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'learn';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-actions.learn.single.label'));

        $this->defaultColor('primary');

        $this->groupedIcon(FilamentIcon::resolve('actions::learn-action.grouped') ?? 'heroicon-m-academic-cap');

        $this->icon('heroicon-m-academic-cap');

        // $this->keyBindings(['mod+a']);

        $this->hidden(static function (Model $record): bool {
            return false;
        });

        $this->authorize(function (Model $record): bool {
            return auth()->user()?->can('view', $record);
        });

        $this->url(fn($record) => $record->learn_url, true);
    }
}
