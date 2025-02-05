<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Question;

class QuestionForm extends Form
{
    public $model = null;

    public $question = '';
    public $answer = '';

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

    public function save()
    {
        $data = $this->validate();

        /**
         * @var \App\Models\User
         */
        $user = Auth::user();

        if (!$this->model)
            $user->questions()->create($data);
        else
            $this->model->update($data);

        $this->reset();

    }

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
