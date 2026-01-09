<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubmissionController;

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Classroom Routes
Route::get('/classrooms', [ClassroomController::class, 'index'])->name('classrooms.index');
Route::post('/classrooms', [ClassroomController::class, 'store'])->name('classrooms.store');
Route::get('/classrooms/{classroom}', [ClassroomController::class, 'show'])->name('classrooms.show');
Route::post('/classrooms/join', [ClassroomController::class, 'join'])->name('classrooms.join');

// Assignment Routes
Route::post('/classrooms/{classroom}/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');

// Submission Routes
Route::post('/assignments/{assignment}/submit', [SubmissionController::class, 'store'])->name('submissions.store');
Route::post('/submissions/{submission}/grade', [SubmissionController::class, 'grade'])->name('submissions.grade');
Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');