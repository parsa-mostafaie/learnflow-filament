<?php

// Import necessary classes
use Illuminate\Support\Facades\Route;

// Route for the welcome page
Route::view('/', 'welcome')->name('welcome');

// Route for the dashboard page, accessible only to authenticated and verified users
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::middleware(['auth', 'verified', 'can:manage some thing'])->as('admin')->prefix('admin')->group(function () {
//     // Admin home page
//     Route::view('/', 'admin');

//     // Admin questions page
//     Route::view('questions', 'admin.questions')
//         ->name('.questions')
//         ->can('manage any questions');

//     // Admin courses page
//     Route::view('courses', 'admin.courses')
//         ->name('.courses')
//         ->can('manage any courses');

//     // Developer users page
//     Route::view('users', 'admin.users')
//         ->name('.users')
//         ->can('manage users or activities');
// });

// Route for the profile page, accessible only to authenticated users
// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

// // Route for the single course page, with a middleware to perform daily tasks
Route::view('courses/{id}', 'course.single')
    ->name('course.single');

// Route::view('report/{id}', 'report')
//     ->name('course.report')
//     ->middleware('auth');

// Route::get('/download-sample-import', function () {
//     return response()->download(public_path('import-sample.xlsx'));
// })->name('download.sample');

// Include authentication routes
require __DIR__ . '/auth.php';
