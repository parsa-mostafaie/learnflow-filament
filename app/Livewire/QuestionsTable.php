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

class QuestionsTable extends DataTableComponent
{
    use Traits\SpinnerPlaceholder;
    protected $model = Question::class;

    protected $listeners = ['questions-table-reload' => '$refresh'];

    public function builder(): Builder
    {
        return Question::with('author');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'desc');
    }
    public function filters(): array
    {
        return [
            BooleanFilter::make("My questions")
                ->filter(function (Builder $builder, bool $enabled) {
                    if ($enabled) {
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
                ->sortable()->searchable(),
            Column::make("Question Text", "question")
                ->sortable()->searchable(),
            Column::make("Question Answer", "answer")
                ->sortable()->searchable(),
            Column::make("Author", "user_id")
                ->format(
                    fn($value, $row, Column $column) => $row->author->name
                ),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
            LivewireComponentColumn::make('Actions', 'id')
                ->component('questions.actions')
                ->attributes(fn($value) => ['question' => $value]),
        ];
    }
}
