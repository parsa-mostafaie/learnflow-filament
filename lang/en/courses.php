<?php

return [
  'singular' => "Course",
  'plural' => "Courses",
  "widgets" => [
    'total_count' => "Total Courses",
    'total_enrolls' => "Total Enrolls",
  ],
  "filters" => [
    'deletion_range' => "Deletion Range",
    'author' => "Author",
    'creation_range' => "Creation Range",
    "updation_range" => "Updation Range",
    "my_courses" => "My Courses",
  ],
  'columns' => [
    'title' => "Title",
    'slug' => "Slug",
    "thumbnail" => "Thumbnail",
    "author" => "Author",
    "id" => "Id",
    "description" => "Description",
    "deleted_at" => "Deleted At",
    "created_at" => "Created At",
    "updated_at" => "Updated At",
    "enrolls_count" => "Enrolled users",
    "all_questions_count" => "Total questions",
    "approved_questions_count" => "Approved questions",
    "rejected_questions_count" => "Rejected questions",
    "pending_questions_count" => "Pending questions"
  ],
  'pages' =>
    [
      'edit' => "Edit Course",
      'view' => "View Course"
    ],
  'sections' => [
    'main_info' => 'Main Information',
    'main_info_desc' => 'View the general details of the course.',
    'meta' => 'Metadata',
    'details' => "Details"
  ],
  'placeholders' => [
    'title' => 'No title provided! 😶‍🌫️',
    'slug' => 'Slug is empty 🕵️‍♂️',
    'description' => 'No description available 🤷‍♂️',
    'created_at' => 'No creation date 😵‍💫',
    'updated_at' => 'No update date 😴',
    'deleted_at' => 'Not Deleted 😶‍🌫️',
  ],
];