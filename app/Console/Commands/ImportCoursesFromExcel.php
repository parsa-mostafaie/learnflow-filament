<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\Question;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

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
            // Create the course using the sheet name
            $course = Course::create(['title' => $index + 7, 'slug' => Str::slug("english-" . ($index + 7) . "th"), 'user_id' => 1]);

            // Iterate over rows and create questions
            foreach ($sheet as $index => $row) {
                if ($index < 1) {
                    continue;
                }

                $question = Question::create([
                    'question' => $row[1],
                    'answer' => $row[2],
                    'user_id' => 1
                ]);

                // Assign the question to the course
                $course->questions()->save($question);
            }
        }

        $this->info('Courses and questions imported successfully!');
    }
}
