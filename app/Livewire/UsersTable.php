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
use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Class UsersTable
 * 
 * This table component is responsible for displaying and managing users.
 */
class UsersTable extends DataTableComponent
{
    use Traits\SpinnerPlaceholder, Traits\TableCustomizations;
    protected $model = User::class;

    // Change the page URL parameter for pagination
    public ?string $pageName = 'users';

    // A unique name to identify the table in session variables
    public string $tableName = 'users';

    protected $listeners = ['users-table-reload' => '$refresh'];

    public function builder(): Builder
    {
        return User::query()->withCount('courses', 'enrolledCourses');
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
            SelectFilter::make(__('Role'))->options([
                '' => __('All'),
                ...User::query()
                    ->distinct()
                    ->pluck('role') // Fetch unique role values
                    ->mapWithKeys(fn($value) => [$value => __(User::getRoleName($value))]) // Transform to key-value pair
                    ->toArray()
            ])
                ->filter(function ($builder, $value) {
                    if ($value)
                        $builder->where('users.role', '>=', $value);
                }),
            SelectFilter::make(__("Verify state"))
                ->options(['' => __('All'), 1 => __('Not Verified'), 2 => __("Verified")])
                ->filter(function (Builder $builder, $value) {
                    if ($value == 2) {
                        $builder->whereNotNull('email_verified_at');
                    } else if ($value == 1) {
                        $builder->whereNull('email_verified_at');
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
            Column::make(__("Name"), "name")
                ->sortable()
                ->searchable(),
            LinkColumn::make(__("Email"), "email")
                ->title(fn($row) => $row->email)
                ->location(fn($row) => "mailto:{$row->email}")
                ->attributes(fn($row) => ['class' => 'text-purple-500'])
                ->sortable()
                ->searchable(),
            ComponentColumn::make(__('Role'), 'role')
                ->component('components.pill')
                ->attributes(fn($value, $row, Column $column) => [
                    'color' => match ($row->role) {
                        0 => 'bg-gray-500',
                        1 => 'bg-blue-500',
                        2 => 'bg-purple-500',
                        3 => 'bg-green-500',
                        default => 'bg-green-500',
                    },
                    'content' => __($row->role_name)
                ]),
            CountColumn::make(__('Courses'))
                ->setDataSource('courses')
                ->sortable(),
            CountColumn::make(__('Enrolled Courses'))
                ->setDataSource('enrolledCourses')
                ->sortable(),
            Column::make(__("Verified at"), "email_verified_at")
                ->sortable(),
            Column::make(__("Created at"), "created_at")
                ->sortable(),
            Column::make(__("Updated at"), "updated_at")
                ->sortable(),
            LivewireComponentColumn::make(__('Actions'), 'id')
                ->component('users.actions')
                ->attributes(fn($value) => ['user' => $value])
        ];
    }
}
