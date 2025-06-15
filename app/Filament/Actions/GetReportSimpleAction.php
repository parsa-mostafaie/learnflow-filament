<?php

namespace App\Filament\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;

class GetReportSimpleAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'report';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-actions.report.single.label'));

        $this->color('gray');

        $this->groupedIcon(FilamentIcon::resolve('actions::report-action.grouped') ?? 'heroicon-m-arrow-trending-up');

        $this->icon('heroicon-m-arrow-trending-up');

        // $this->keyBindings(['mod+a']);

        $this->hidden(static function (Model $record): bool {
            return !$record->isEnrolledBy(auth()->user() ?? null);
        });

        $this->authorize(function (Model $record): bool {
            return auth()->user()?->can('getReport', $record);
        });

        $this->url(fn($record) => $record->report_url);
    }
}
