<?php

namespace App\Filament\Resources\CourseResource\Widgets;

use App\Models\Course;
use App\Models\CourseUser;
use Filament\Support\Colors\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Filament\Support\Enums\IconPosition;

class CourseOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $total_count_trend =
            Trend::model(Course::class)
                ->between(
                    start: now()->startOfYear(),
                    end: now()->lastOfMonth(),
                )
                ->perMonth()
                ->count()
                ->map(fn($value) => $value->aggregate)
                ->cumulativeSum();

        $total_count_info = changeInfo($total_count_trend);

        $total_enrolls_trend =
            Trend::model(CourseUser::class)
                ->between(
                    start: now()->startOfYear(),
                    end: now()->lastOfMonth(),
                )
                ->perMonth()
                ->count()
                ->map(fn($value) => $value->aggregate)
                ->cumulativeSum();

        $total_enrolls_info = changeInfo($total_enrolls_trend);

        return [
            Stat::make(
                __('courses.widgets.total_count'),
                forhumans(Course::withTrashed()->count())
            )
                ->icon('heroicon-o-academic-cap')
                ->color($total_count_info['color'])
                ->chart($total_count_trend->toArray())
                ->description($total_count_info['description'])
                ->descriptionIcon($total_count_info['icon'], IconPosition::Before),
            Stat::make(
                __('courses.widgets.total_enrolls'),
                forhumans(CourseUser::count())
            )
                ->icon('heroicon-o-users')
                ->color($total_enrolls_info['color'])
                ->chart($total_enrolls_trend->toArray())
                ->description($total_enrolls_info['description'])
                ->descriptionIcon($total_enrolls_info['icon'], IconPosition::Before)
        ];
    }

    /**
     * Determine if the user can view the course overview widget.
     *
     * @return bool
     */
    public static function canView(): bool
    {
        return auth()->user()->can('viewOverview', Course::class);
    }
}
