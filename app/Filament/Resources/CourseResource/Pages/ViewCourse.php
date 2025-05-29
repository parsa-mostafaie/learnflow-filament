<?php

namespace App\Filament\Resources\CourseResource\Pages;

use Rmsramos\Activitylog\Actions\ActivityLogTimelineSimpleAction;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            ActivityLogTimelineSimpleAction::make('Activities')
                ->authorize(fn() => auth()->user()->can('manage any activities'))
                ->label(__('tables.actions.activities')),
        ];
    }
}
