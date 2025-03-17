<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\CountColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LivewireComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\BooleanFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectDropdownFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Support\Arr;
use Spatie\Activitylog\Models\Activity as Activity;

/**
 * Class ActivitiesTable
 * 
 * This table component is responsible for displaying and managing activities.
 */
class ActivitiesTable extends DataTableComponent
{
    use Traits\SpinnerPlaceholder, Traits\TableCustomizations;
    protected $model = Activity::class;

    // Change the page URL parameter for pagination
    public ?string $pageName = 'activities';

    // A unique name to identify the table in session variables
    public string $tableName = 'activities';

    protected $listeners = ['activities-table-reload' => '$refresh'];

    /**
     * Define the query builder for the activities.
     * 
     * @return Builder
     */
    public function builder(): Builder
    {
        // Retrieve activities, and eager load the relationships
        return Activity::with('causer', 'subject');
    }

    /**
     * Configure the data table.
     * 
     * Set the primary key and default sorting for the table.
     */
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }

    /**
     * Define the filters for the data table.
     * 
     * @return array
     */
    public function filters(): array
    {
        return [
            SelectFilter::make(__("Subject type"))
                ->options(
                    [
                        '' => __("All"),
                        ...Activity::query()
                            ->distinct()
                            ->pluck('subject_type') // Fetch unique subject_type values
                            ->mapWithKeys(fn($value) => [$value => __(class_basename($value))]) // Transform to key-value pair
                            ->toArray()
                    ]
                )
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('subject_type', $value);
                    }
                }),
            SelectFilter::make(__("Event"))
                ->options(
                    [
                        '' => __("All"),
                        ...Activity::query()
                            ->distinct()
                            ->pluck('event') // Fetch unique event values
                            ->mapWithKeys(fn($value) => [$value => __($value)]) // Transform to key-value pair
                            ->toArray()
                    ]
                )
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('event', $value);
                    }
                }),
            SelectFilter::make(__("Log name"))
                ->options(
                    [
                        '' => __("All"),
                        ...Activity::query()
                            ->distinct()
                            ->pluck('log_name') // Fetch unique log_name values
                            ->mapWithKeys(fn($value) => [$value => __($value)]) // Transform to key-value pair
                            ->toArray()
                    ]
                )
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('log_name', $value);
                    }
                }),
        ];
    }

    /**
     * Define the columns for the data table.
     * 
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__("Id"), "id")
                ->sortable()
                ->searchable(),
            ComponentColumn::make(__('Log name'), 'log_name')
                ->component('components.pill')
                ->attributes(fn($value, $row, Column $column) => [
                    'color' => match ($row->log_name) {
                        'default' => 'bg-gray-500',
                        'Authentication' => 'bg-purple-500',
                        default => 'bg-green-500',
                    },
                    'content' => __($row->log_name)
                ])
                ->searchable(),
            ComponentColumn::make(__('Description'), 'description')
                ->component('components.pill')
                ->attributes(fn($value, $row, Column $column) => [
                    'color' => match ($row->description) {
                        'created' => 'bg-green-500',
                        'deleted' => 'bg-red-500',
                        'restored' => 'bg-blue-500',
                        'updated' => 'bg-purple-500',
                        'promoted' => 'bg-green-400',
                        'demoted' => 'bg-red-400',
                        'Login' => 'bg-green-700',
                        'Log Out' => 'bg-red-700',
                        'unenrolled' => 'bg-red-400',
                        'enrolled' => 'bg-green-400',
                        '' => 'bg-green-600',
                        default => null,
                    },
                    'content' => __($row->description)
                ]),
            ComponentColumn::make(__('Event'), 'event')
                ->component('components.pill')
                ->attributes(fn($value, $row, Column $column) => [
                    'color' => match ($row->event) {
                        'created' => 'bg-green-500',
                        'deleted' => 'bg-red-500',
                        'restored' => 'bg-blue-500',
                        'updated' => 'bg-purple-500',
                        'promoted' => 'bg-green-400',
                        'demoted' => 'bg-red-400',
                        'Login' => 'bg-green-700',
                        'Log Out' => 'bg-red-700',
                        'unenrolled' => 'bg-red-400',
                        'enrolled' => 'bg-green-400',
                        default => null,
                    },
                    'content' => __($row->event)
                ]),
            Column::make(__("Subject"), 'subject_id')
                ->format(fn($value, $row, Column $column) =>
                    $row->subject?->activitySubjectStamp() ?? view(
                        'components.pill',
                        ['content' => __('Deleted') . ": $value", 'color' => 'bg-red-600']
                    )),
            Column::make(__('Subject type'), 'subject_type')->format(fn($value) => __(class_basename($value))),
            Column::make(__('Causer type'), 'causer_type')->format(fn($value) => __(class_basename($value))),
            Column::make(__("Causer"), "causer_id")
                ->format(fn($value, $row) => $row->causer?->email),
            ComponentColumn::make(__("Changes"), 'properties')
                ->component('components.property_diff')
                ->attributes(fn($value, $row, Column $column) => ['data' => $row->changes])
                ->collapseAlways(),
            ComponentColumn::make(__("Additional Properties"), 'properties')
                ->component('components.data-grid')
                ->attributes(fn($value, $row, Column $column) => ['data' => Arr::except($row->properties, ['attributes', 'old'])])
                ->collapseAlways(),
            Column::make(__("Created at"), "created_at"),
            Column::make(__("Updated at"), "updated_at")
                ->sortable(),
        ];
    }
}
