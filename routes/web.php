<?php

// Import necessary classes
use Illuminate\Support\Facades\Route;

// Route for the welcome page
Route::view('/', 'welcome')->name('welcome');

// Route for the dashboard page, accessible only to authenticated and verified users
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin routes, accessible only to authenticated and verified users with the 'admin' role
Route::middleware(['auth', 'verified', 'isRole:admin'])->as('admin')->prefix('admin')->group(function () {
    // Admin home page
    Route::view('/', 'admin');

    // Admin questions page
    Route::view('questions', 'admin.questions')
        ->name('.questions');

    // Admin courses page
    Route::view('courses', 'admin.courses')
        ->name('.courses');

    // Developer users page
    Route::view('users', 'admin.users')
        ->name('.users')
        ->can('viewAny', 'App\\Models\\User');
});

// Route for the profile page, accessible only to authenticated users
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route for the single course page, with a middleware to perform daily tasks
Route::view('courses/{id}', 'course.single')
    ->name('course.single')->middleware("perform_daily_task");

// Include authentication routes
require __DIR__ . '/auth.php';
