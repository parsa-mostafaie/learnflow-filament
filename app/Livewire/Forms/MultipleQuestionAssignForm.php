<?php

namespace App\Livewire\Forms;

use App\Models\Question;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Maatwebsite\Excel\Facades\Excel;
use Masmerise\Toaster\Toaster;

class MultipleQuestionAssignForm extends Form
{
    public $file = null;
    public $course = null;

    public function rules()
    {
        return [
            'file' => [
                'required',                // Ensure the file is provided
                'file',                    // Validate it is a file
                'mimes:xlsx,xls,csv',      // Allow Excel and CSV formats
                'max:2048',                // Limit file size to 2MB
            ]
        ];
    }

    public function save()
    {
        $this->validate();

        $excel = Excel::toCollection(null, $this->file->getRealPath());
        $sheet = $excel[0];

        // Iterate over rows and create questions
        foreach ($sheet as $rowIndex => $row) {
            if ($rowIndex < 1) {
                continue;
            }

            try {
                if (!$row[1] || !$row[2]) {
                    continue;
                }

                $question = Question::firstOrCreate([
                    'question' => $row[1],
                    'answer' => $row[2],
                ], [
                    'user_id' => auth()->id(),
                    'status' => \isRole('developer') ? 'approved' : 'pending'
                ]);

                // Assign the question to the course
                $this->course->questions()->save($question);
            } catch (QueryException $e) {
                if ($e->getCode() == 23000) { // SQLSTATE[23000] indicates a uniqueness constraint violation
                    //Toaster::error('Question with text="' . $row[1] . '" and answer="' . $row[2] . '" found more than once.');
                } else {
                    // Log other SQL errors if needed
                    Toaster::error('An error occurred: ' . $e->getMessage());
                }
            }
        }

        $this->reset();
    }

    public function setModel($course = null)
    {
        $this->course = $course;

        return $this->course;
    }
}
