<?php

namespace App\Filament\Imports;

use App\Enums\Status;
use App\Models\Question;
use Illuminate\Support\Facades\Log;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class QuestionImporter extends Importer
{
    protected static ?string $model = Question::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('question')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->label(__('questions.importer.question'))
                ->exampleHeader(__('questions.importer.question')),
            ImportColumn::make('answer')
                ->requiredMapping()
                ->rules([
                    'required',
                    'max:255',
                ])
                ->label(__('questions.importer.answer'))
                ->exampleHeader(__('questions.importer.answer'))
        ];
    }

    public function resolveRecord(): ?Question
    {
        $question = trim($this->data['question']);
        $answer = trim($this->data['answer']);

        $existing = Question::where('question', $question)
            ->where('answer', $answer)
            ->first();

        if ($existing) {
            if (!blank($this->options['course_id']))
                $existing->courses()->syncWithoutDetaching([$this->options['course_id']]);

            return null;
        }

        return new Question([
            'question' => $question,
            'answer' => $answer,
            'user_id' => auth()->id(),
            'status' => Gate::allows('create approved questions') ? Status::Approved : Status::Pending,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = trans_choice('questions.messages.import_success', $import->successful_rows, [
            'count' => $import->successful_rows,
        ]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    protected function afterSave(): void
    {
        if (!blank($this->options['course_id']))
            $this->record->courses()->syncWithoutDetaching([$this->options['course_id']]);
    }
}
