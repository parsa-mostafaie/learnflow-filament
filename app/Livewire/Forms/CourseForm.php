<?php

namespace App\Livewire\Forms;

use App\Models\Course;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Masmerise\Toaster\Toaster;
use Milwad\LaravelValidate\Rules\ValidSlug;

/**
 * Class CourseForm
 * 
 * This form component is responsible for handling the creation and updating of courses.
 */
class CourseForm extends Form
{
    public $course = null;
    public $title = '';
    public $description = '';
    public $slug = '';
    public $thumbnail = null;

    /**
     * Define the validation rules for the form fields.
     * 
     * @return array
     */
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
            'thumbnail' => ['nullable', File::types(['jpg', 'png', 'jpeg'])->max(1024)],
        ];
    }

    /**
     * Save the course data.
     * 
     * This method validates the form data, generates a slug if needed, stores the thumbnail,
     * and creates or updates the course record in the database.
     */
    public function save()
    {
        // Generate slug if not provided
        $this->slug = $this->slug ?: Str::slug($this->title);

        try {
            // Validate the form data
            $data = $this->validate();
        } catch (ValidationException $e) {
            Toaster::error(\__('Having Some validation errors'));
            throw $e;
        }

        // Store the thumbnail image if provided
        if (!empty($data['thumbnail'])) {
            $data['thumbnail'] = $data['thumbnail']->store('course-thumbnails');

            if ($this->course) {
                // Remove the previous image if updating an existing course
                $this->course->removePreviousImage();
            }
        } else if (isset($data['thumbnail'])) {
            unset($data['thumbnail']);
        }

        /**
         * @var \App\Models\User
         * 
         * Get the authenticated user
         */
        $user = Auth::user();

        // Create or update the course
        if (!$this->course) {
            $user->courses()->create($data);
        } else {
            $this->course->update($data);
        }

        // Reset the form fields
        $this->reset();
    }

    /**
     * Sync the selected questions with the course.
     * 
     * @param array $selectedQuestions
     */
    public function sync($selectedQuestions)
    {
        $this->course->questions()->sync($selectedQuestions);
    }

    /**
     * Set the model for the form.
     * 
     * @param \App\Models\Course|null $course
     * @return \App\Models\Course|null
     */
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

    public function tempUrl()
    {
        $alternative = $this->course?->image_url ?? null;

        try {
            return $this->thumbnail?->temporaryUrl() ?? $alternative;
        } catch (Exception $exception) {
            return $alternative;
        }
    }
}
