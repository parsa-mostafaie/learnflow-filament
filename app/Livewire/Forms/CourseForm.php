<?php

namespace App\Livewire\Forms;

use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Milwad\LaravelValidate\Rules\ValidSlug;

class CourseForm extends Form
{
    public $course = null;

    public $title = '';
    public $description = '';
    public $slug = '';
    public $thumbnail = null;

    public function rules()
    {
        $unique = Rule::unique(Course::class);

        if ($this->course) {
            $unique->ignoreModel($this->course);
        }

        return [
            'title' => 'required|string|max:256',
            'description' => 'nullable|string|max:2048',
            'slug' => ['nullable', 'string', new ValidSlug, $unique],
            'thumbnail' => 'nullable|image|max:1024',
        ];
    }

    public function save()
    {
        $data = $this->validate();

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        if ($data['thumbnail']) {
            $data['thumbnail'] = $data['thumbnail']->store('course-thumbnails');

            if ($this->course)
                $this->course->removePreviousImage();
        }

        /**
         * @var \App\Models\User
         */
        $user = Auth::user();

        if (!$this->course)
            $user->courses()->create($data);
        else
            $this->course->update($data);

        $this->reset();
    }

    public function setModel($course = null)
    {
        $this->course = $course;

        if ($this->course) {
            $this->title = $this->course->title;
            $this->description = $this->course->description;
            $this->slug = $this->course->slug;
        }

        return $this->course;
    }
}
