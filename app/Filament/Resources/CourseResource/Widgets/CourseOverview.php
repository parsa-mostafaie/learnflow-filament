<?php

namespace App\Filament\Resources\CourseResource\Widgets;

use App\Models\Course;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourseOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('courses.widgets.total_count'), Course::withTrashed()->count())->icon('heroicon-o-academic-cap')->color('primary'),
            Stat::make(__('courses.widgets.total_enrolls'), Course::withCount('enrolls')->get(['enrolls_count'])->sum('enrolls_count'))->icon('heroicon-o-users')->color('info'),
        ];
    }
}
