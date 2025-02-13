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
use App\Models\Question;

/**
 * Class QuestionsTable
 * 
 * This table component is responsible for displaying and managing questions.
 */
class QuestionsTable extends DataTableComponent
{
    use Traits\SpinnerPlaceholder;
    protected $model = Question::class;

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
            Column::make(__("Created at"), "created_at")
                ->sortable(),
            Column::make(__("Updated at"), "updated_at")
                ->sortable(),
            LivewireComponentColumn::make(__('Actions'), 'id')
                ->component('questions.actions')
                ->attributes(fn($value) => ['question' => $value]),
        ];
    }
}
