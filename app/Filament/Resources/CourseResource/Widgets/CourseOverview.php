<?php

namespace App\Filament\Resources\CourseResource\Widgets;

use App\Models\Course;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourseOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $total_count_trend = Trend::model(Course::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make(__('courses.widgets.total_count'), forhumans(Course::withTrashed()->count()))->icon('heroicon-o-academic-cap')->color('primary')->chart([50, 60, 30, 20, 70]),
            Stat::make(__('courses.widgets.total_enrolls'), forhumans(Course::withCount('enrolls')->get(['enrolls_count'])->sum('enrolls_count')))->icon('heroicon-o-users')->color('info'),
        ];
    }
}
