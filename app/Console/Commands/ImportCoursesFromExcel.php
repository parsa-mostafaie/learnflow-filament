<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\Question;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

/**
 * Class ImportCoursesFromExcel
 *
 * Handles importing courses and related questions from an Excel file.
 */
class ImportCoursesFromExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:courses {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import courses and questions from an Excel file';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');

        // Read the Excel file and convert to collection
        $sheets = Excel::toCollection(null, $file);

        foreach ($sheets as $index => $sheet) {
            // Ask if the user wants to import this sheet
            if (!$this->confirm('Do you want to import the sheet: ' . ($index + 1) . '?')) {
                continue;
            }

            // Extract potential default values from Excel
            $defaultTitle = $sheet[2][6] ?? ''; // Column G, Row 3 (0-based index)
            $defaultDescription = $sheet[2][7] ?? ''; // Column H, Row 3 (optional)

            // Validate required fields and prompt with default values
            do {
                $title = $this->ask('Enter course title (required, default: ' . $defaultTitle . ')') ?: $defaultTitle;

                if (empty($title)) {
                    $this->error('Title is required.');
                }
            } while (empty($title));

            $defaultSlug = !empty($sheet[2][8]) ? $sheet[2][8] : Str::slug($title); // Column I, Row 3

            do {
                $slug = $this->ask('Enter course slug (required, default: ' . $defaultSlug . ')') ?: $defaultSlug;

                if (empty($slug)) {
                    $this->error('Slug is required.');
                }
            } while (empty($slug));

            // Ensure the slug is unique
            while (Course::where('slug', $slug)->exists()) {
                $this->error('The slug "' . $slug . '" is already in use. Please provide a unique slug.');
                $slug = $this->ask('Enter unique course slug (default: ' . Str::slug($title) . ')') ?: Str::slug($title);
            }

            // Create the course with title, slug, and optional description
            $course = Course::create([
                'title' => $title,
                'slug' => $slug,
                'description' => $defaultDescription,
                'user_id' => 1
            ]);

            // Iterate over rows to create associated questions
            foreach ($sheet as $rowIndex => $row) {
                if ($rowIndex < 1) {
                    continue;
                }

                try {
                    // Ensure the question and answer are set
                    if (empty($row[1]) || empty($row[2])) {
                        $this->error('Skipping row ' . ($rowIndex + 1) . ' due to missing required question or answer.');
                        continue;
                    }

                    // Create or find question entry
                    $question = Question::firstOrCreate([
                        'question' => $row[1],
                        'answer' => $row[2],
                    ], [
                        'user_id' => 1,
                        'status' => 'approved'
                    ]);

                    // Associate the question with the course
                    $course->questions()->save($question);
                } catch (QueryException $e) {
                    if ($e->getCode() == 23000) { // Unique constraint violation
                        $this->error('Duplicate question: "' . $row[1] . '" with answer "' . $row[2] . '"');
                    } else {
                        $this->error('SQL Error: ' . $e->getMessage());
                    }
                }
            }
        }

        $this->info('Courses and questions imported successfully!');
    }
}
