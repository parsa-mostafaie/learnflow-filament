<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\CountColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LivewireComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\BooleanFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Class CoursesTable
 * 
 * This table component is responsible for displaying and managing courses.
 */
class CoursesTable extends DataTableComponent
{
    use Traits\SpinnerPlaceholder, Traits\TableCustomizations;
    protected $model = Course::class;

    // Change the page URL parameter for pagination
    public ?string $pageName = 'courses';

    // A unique name to identify the table in session variables
    public string $tableName = 'courses';

    protected $listeners = ['courses-table-reload' => '$refresh'];

    /**
     * Define the query builder for the courses.
     * 
     * @return Builder
     */
    public function builder(): Builder
    {
        // Retrieve courses, including soft-deleted ones, and eager load the author relationship
        return Course::withTrashed()->with('author')->withCount('enrolls')->withCount('questions');
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
            BooleanFilter::make(__("My courses"))
                ->filter(function (Builder $builder, bool $enabled) {
                    if ($enabled && Auth::user()) {
                        $builder->whereBelongsTo(Auth::user());
                    }
                })
                ->setFilterDefaultValue(false)
                ->setInputAttributes($this->getFilterAttributes()),
            SelectFilter::make(__("Deletion state"))
                ->options(['' => __('All'), 1 => __('Not Soft Deleteds'), 2 => __("Soft Deleteds")])
                ->filter(function (Builder $builder, $value) {
                    if ($value == 2) {
                        $builder->whereNotNull('deleted_at');
                    } else if ($value == 1) {
                        $builder->whereNull('deleted_at');
                    }
                })
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
            Column::make(__("Title"), "title")
                ->sortable()
                ->searchable(),
            Column::make(__("Description"), "description")
                ->sortable()
                ->searchable(),
            CountColumn::make(__('Enrolled Users'))
                ->setDataSource('enrolls')
                ->sortable(),
            CountColumn::make(__('Questions'))
                ->setDataSource('questions')
                ->sortable(),
            Column::make(__("Author"), "user_id")
                ->format(
                    fn($value, $row, Column $column) => $row->author->name
                ),
            Column::make(__("Slug"), "slug")
                ->sortable()
                ->searchable(),
            Column::make(__("Created at"), "created_at")
                ->sortable(),
            Column::make(__("Updated at"), "updated_at")
                ->sortable(),
            Column::make(__("Deleted at"), "deleted_at")
                ->sortable(),
            LivewireComponentColumn::make(__('Actions'), 'id')
                ->component('courses.actions')
                ->attributes(fn($value) => ['course' => $value]),
        ];
    }
}
