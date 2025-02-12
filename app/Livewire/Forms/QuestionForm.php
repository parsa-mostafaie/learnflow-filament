<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Question;

/**
 * Class QuestionForm
 * 
 * This form component is responsible for handling the creation and updating of questions.
 */
class QuestionForm extends Form
{
    public $model = null;

    public $question = '';
    public $answer = '';

    /**
     * Define the validation rules for the form fields.
     * 
     * @return array
     */
    public function rules()
    {
        $unique = Rule::unique(Question::class)->where(function ($query) {
            return $query->where('question', $this->question)
                ->where('answer', $this->answer);
        });

        if ($this->model) {
            $unique->ignoreModel($this->model);
        }

        return [
            'question' => ['required', 'string', 'max:256', $unique],
            'answer' => ['required', 'string', 'max:256'],
        ];
    }

    /**
     * Save the question data.
     * 
     * This method validates the form data and creates or updates the question record in the database.
     */
    public function save()
    {
        // Validate the form data
        $data = $this->validate();

        /**
         * @var \App\Models\User
         * 
         * Get the authenticated user
         */
        $user = Auth::user();

        // Create or update the question
        if (!$this->model) {
            $user->questions()->create($data);
        } else {
            $this->model->update($data);
        }

        // Reset the form fields
        $this->reset();

    }

    /**
     * Set the model for the form.
     * 
     * @param \App\Models\Question|null $question
     * @return \App\Models\Question|null
     */
    public function setModel($question = null)
    {
        $this->model = $question;

        if ($this->model) {
            $this->question = $this->model->question;
            $this->answer = $this->model->answer;
        }

        return $this->model;
    }
}
