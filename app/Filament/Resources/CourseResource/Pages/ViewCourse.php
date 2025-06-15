<?php

namespace App\Filament\Resources\CourseResource\Pages;

use Rmsramos\Activitylog\Actions\ActivityLogTimelineSimpleAction;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;
use App\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Actions\LearnSimpleAction;
use App\Filament\Actions\EnrollToggleSimpleAction;
use App\Filament\Actions\GetReportSimpleAction;

class ViewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            LearnSimpleAction::make(),
            GetReportSimpleAction::make(),
            EnrollToggleSimpleAction::make(),
            ActivityLogTimelineSimpleAction::make('Activities')
                ->authorize(fn() => auth()->user()->can('manage any activities'))
                ->label(__('tables.actions.activities')),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('courses.pages.view');
    }
}
