<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\CountColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LivewireComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\BooleanFilter;

class CoursesTable extends DataTableComponent
{
    use Traits\SpinnerPlaceholder;
    protected $model = Course::class;

    protected $listeners = ['courses-table-reload' => '$refresh'];

    public function builder(): Builder
    {
        return Course::withTrashed()->with('author');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }

    public function filters(): array
    {
        return [
            BooleanFilter::make("My courses")
                ->filter(function (Builder $builder, bool $enabled) {
                    if ($enabled && Auth::user()) {
                        $builder->whereBelongsTo(Auth::user());
                    }
                })
                ->setFilterDefaultValue(false)
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->searchable(),
            Column::make("Title", "title")
                ->sortable()
                ->searchable(),
            Column::make("Description", "description")
                ->sortable()
                ->searchable(),
            CountColumn::make('Enrolled Users')
                ->setDataSource('enrolls')
                ->sortable(),
            CountColumn::make('Questions')
                ->setDataSource('questions')
                ->sortable(),
            Column::make("Author", "user_id")
                ->format(
                    fn($value, $row, Column $column) => $row->author->name
                ),
            Column::make("Slug", "slug")
                ->sortable()
                ->searchable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
            Column::make("Deleted at", "deleted_at")
                ->sortable(),
            LivewireComponentColumn::make('Actions', 'id')
                ->component('courses.actions')
                ->attributes(fn($value) => ['course' => $value]),
        ];
    }
}
