<?php

return [
  'singular' => 'Question',
  'plural' => 'Questions',
  'columns' => [
    'question' => 'Question',
    'answer' => 'Answer',
    'author' => 'Author',
    'status' => 'Status',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
  ],
  'statuses' => [
    'pending' => 'Pending',
    'approved' => 'Approved',
    'rejected' => 'Rejected',
  ],
  'filters' => [
    'author' => "Author",
    "status" => "Status",
    'creation_range' => "Creation Range",
    "updation_range" => "Updation Range",
  ],
  'pages' =>
    [
      'edit' => "Edit Question",
      'view' => "View Question"
    ],
  'messages' => [
    'import_success' => '{1} Your question import has completed and :count row imported.|[2,*] Your question import has completed and :count rows imported.',
  ],
  'importer' => [
    'question' => 'Question',
    'answer' => 'Answer',
  ],
];
