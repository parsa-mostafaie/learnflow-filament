<?php

namespace App\Livewire;

use App\Models\Question;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\CountColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LivewireComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\BooleanFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Class QuestionsTable
 * 
 * This table component is responsible for displaying and managing questions.
 */
class QuestionsTable extends DataTableComponent
{
    use Traits\SpinnerPlaceholder, Traits\TableCustomizations;
    protected $model = Question::class;

    // Change the page URL parameter for pagination
    public ?string $pageName = 'questions';

    // A unique name to identify the table in session variables
    public string $tableName = 'questions';

    protected $listeners = ['questions-table-reload' => '$refresh'];

    /**
     * Define the query builder for the questions.
     * 
     * @return Builder
     */
    public function builder(): Builder
    {
        // Retrieve questions and eager load the author relationship
        return Question::with('author');
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
            BooleanFilter::make(__("My questions"))
                ->filter(function (Builder $builder, bool $enabled) {
                    if ($enabled) {
                        $builder->whereBelongsTo(Auth::user());
                    }
                })
                ->setFilterDefaultValue(false)
                ->setInputAttributes($this->getFilterAttributes()),
            SelectFilter::make(__("Status"))
                ->options(
                    [
                        '' => __("All"),
                        ...Question::query()
                            ->distinct()
                            ->pluck('status') // Fetch unique status values
                            ->mapWithKeys(fn($value) => [$value => __($value)]) // Transform to key-value pair
                            ->toArray()
                    ]
                )
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('status', $value);
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
            Column::make(__("Question Text"), "question")
                ->sortable()
                ->searchable(),
            Column::make(__("Question Answer"), "answer")
                ->sortable()
                ->searchable(),
            Column::make(__("Author"), "user_id")
                ->format(
                    fn($value, $row, Column $column) => $row->author->name
                ),
            ComponentColumn::make(__('Status'), 'status')
                ->component('components.pill')
                ->attributes(fn($value, $row, Column $column) => [
                    'color' => match ($row->status) {
                        'approved' => 'bg-green-500',
                        'rejected' => 'bg-red-500',
                        'pending' => 'bg-blue-500',
                        default => null,
                    },
                    'content' => __($row->status)
                ]),
            Column::make(__("Created at"), "created_at")
                ->format(fn($value, $row, Column $column) => Jalalian::fromCarbon($value)->format('%A %d %B %Y ساعت %I:%M %P'))
                ->sortable(),
            Column::make(__("Updated at"), "updated_at")
                ->format(fn($value, $row, Column $column) => Jalalian::fromCarbon($value)->format('%A %d %B %Y ساعت %I:%M %P'))
                ->sortable(),
            LivewireComponentColumn::make(__('Actions'), 'id')
                ->component('questions.actions')
                ->attributes(fn($value) => ['question' => $value]),
        ];
    }
}
