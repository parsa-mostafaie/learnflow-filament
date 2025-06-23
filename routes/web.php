<?php

// Import necessary classes
use Illuminate\Support\Facades\Route;

// Route for the welcome page
Route::view('/', 'welcome')->name('welcome');

// Route for the dashboard page, accessible only to authenticated and verified users
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route for the profile page, accessible only to authenticated users
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Route for the single course page
Route::view('courses/{id}', 'course.single')
    ->name('course.single');

Route::view('report/{id}', 'report')
    ->name('course.report')
    ->middleware('auth');

Route::get('/download-sample-import', function () {
    return response()->download(public_path('import-sample.xlsx'));
})->name('download.sample');

// Include authentication routes
require __DIR__ . '/auth.php';
