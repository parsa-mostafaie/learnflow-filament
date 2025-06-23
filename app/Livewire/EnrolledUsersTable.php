<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\CourseUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\CountColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LivewireComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\BooleanFilter;

/**
 * Class EnrolledUsersTable
 * 
 * This table component is responsible for displaying and managing enrolled users.
 */
class EnrolledUsersTable extends DataTableComponent
{
    use Traits\SpinnerPlaceholder, Traits\TableCustomizations;
    protected $model = CourseUser::class;

    // Change the page URL parameter for pagination
    public ?string $pageName = 'enrolled-users';

    // A unique name to identify the table in session variables
    public string $tableName = 'enrolled-users';

    public $course = null;

    protected $listeners = ['enrolled-users-table-reload' => '$refresh'];

    /**
     * Define the query builder for the enrolled-users.
     * 
     * @return Builder
     */
    public function builder(): Builder
    {
        // Retrieve enrolled-users
        return CourseUser::with('user')->where('course_id', $this->course->id ?? null);
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
        return [];
    }

    /**
     * Define the columns for the data table.
     * 
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__("users.columns.id"), "id")
                ->sortable()
                ->searchable(),
            Column::make(__("User"), "user_id")
                ->format(
                    fn($value, $row, Column $column) => $row->user->name
                ),
            Column::make(__("Last Course Visited"), "last_course_visit")
                ->format(fn($value, $row, Column $column) => $value ? Jalalian::fromCarbon($value)->format('%A %d %B %Y ساعت %I:%M %P') : '')
                ->sortable(),
            Column::make(__("Created at"), "created_at")
                ->format(fn($value, $row, Column $column) => Jalalian::fromCarbon($value)->format('%A %d %B %Y ساعت %I:%M %P'))
                ->sortable(),
            Column::make(__("Updated at"), "updated_at")
                ->format(fn($value, $row, Column $column) => Jalalian::fromCarbon($value)->format('%A %d %B %Y ساعت %I:%M %P'))
                ->sortable(),
            LivewireComponentColumn::make(__('Learn Percentage'), 'id')
                ->component('courses.learned_percentage')
                ->attributes(fn($value) => ['course_user' => $value]),
        ];
    }
}
