<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'isRole:admin'])->as('admin')->prefix('admin')->group(function () {
    Route::view('/', 'admin');

    Route::view('questions', 'admin.questions')
        ->name('.questions');

    Route::view('courses', 'admin.courses')
        ->name('.courses');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('courses/{id}', 'course.single')
    ->name('course.single')->middleware("perform_daily_task");

require __DIR__ . '/auth.php';
