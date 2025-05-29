<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use App\Filament\Actions\ApproveAction;
use Filament\Resources\Pages\ViewRecord;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineSimpleAction;

class ViewQuestion extends ViewRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            ActivityLogTimelineSimpleAction::make('Activities')
                ->label(__('tables.actions.activities'))
                ->authorize(fn() => auth()->user()->can('manage any activities')),
        ];
    }
}
