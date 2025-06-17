<?php

return [
  'singular' => "User",
  'plural' => "Users",

  "widgets" => [
    // 
  ],
  "filters" => [
    'creation_range' => "Creation Date Range",
    "updation_range" => "Update Date Range",
    "role" => "Role",
    "email_state" => [
      'label' => "Email Verify State",
      'verified' => 'Verified',
      'unverified' => 'Unverified',
    ],
    "email_verified_range" => "Email Verified At Range",
  ],
  'columns' => [
    'name' => "Name",
    "avatar" => "Avatar",
    "role" => "Role",
    "id" => "ID",
    "email" => "Email",
    "email_verified_at" => "Email Verified At",
    "courses_count" => "Courses Count",
    "enrolled_courses_count" => "Enrolled Courses Count",
    "created_at" => "Created At",
    "updated_at" => "Updated At",
  ],
  'roles' => [
    'user' => 'User',
    'instructor' => 'Instructor',
    'manager' => 'Manager',
    'admin' => 'Administrator',
    'developer' => 'Developer',
    'unknown' => 'Unknown',
  ],
  'pages' => [
    'edit' => "Edit User",
    'view' => "View User",
  ],
  'sections' => [
    // 
  ],
  'placeholders' => [
    // 
  ],
];
