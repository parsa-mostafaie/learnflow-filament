<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\Question;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class ImportCoursesFromExcel extends Command
{
    protected $signature = 'import:courses {file}';
    protected $description = 'Import courses and questions from an Excel file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $file = $this->argument('file');
        $sheets = Excel::toCollection(null, $file);

        foreach ($sheets as $index => $sheet) {
            // Ask if the user wants to import this sheet
            if (!$this->confirm('Do you want to import the sheet: ' . ($index + 1) . '?')) {
                continue;
            }

            // Prompt for course title
            $title = $this->ask('Please enter the title for the course (Sheet: ' . ($index + 1) . ')');

            // Prompt for course slug, default to generated slug if not provided
            $slug = $this->ask('Please enter the slug for the course (default: ' . Str::slug($title) . ')') ?: Str::slug($title);

            // Check for duplicate slugs and re-ask until a unique slug is provided
            while (Course::where('slug', $slug)->exists()) {
                $this->error('The slug "' . $slug . '" is already in use. Please provide a unique slug.');
                $slug = $this->ask('Please enter a unique slug for the course (default: ' . Str::slug($title) . ')') ?: Str::slug($title);
            }

            // Create the course using the provided title and slug
            $course = Course::create(['title' => $title, 'slug' => $slug, 'user_id' => 1]);

            // Iterate over rows and create questions
            foreach ($sheet as $rowIndex => $row) {
                if ($rowIndex < 1) {
                    continue;
                }

                try {
                    $question = Question::create([
                        'question' => $row[1],
                        'answer' => $row[2],
                        'user_id' => 1
                    ]);

                    // Assign the question to the course
                    $course->questions()->save($question);
                } catch (QueryException $e) {
                    if ($e->getCode() == 23000) { // SQLSTATE[23000] indicates a uniqueness constraint violation
                        $this->error('Question with text="' . $row[1] . '" and answer="' . $row[2] . '" found more than once.');
                    } else {
                        // Log other SQL errors if needed
                        $this->error('An error occurred: ' . $e->getMessage());
                    }
                }
            }
        }

        $this->info('Courses and questions imported successfully!');
    }
}
