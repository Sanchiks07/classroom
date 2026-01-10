<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminHistoryController;

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
// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Classroom Routes
Route::get('/classrooms', [ClassroomController::class, 'index'])->name('classrooms.index');
Route::post('/classrooms', [ClassroomController::class, 'store'])->name('classrooms.store');
Route::get('/classrooms/{classroom}', [ClassroomController::class, 'show'])->name('classrooms.show');
Route::put('/classrooms/{classroom}', [ClassroomController::class, 'update'])->name('classrooms.update');
Route::delete('/classrooms/{classroom}', [ClassroomController::class, 'destroy'])->name('classrooms.destroy');
Route::post('/classrooms/join', [ClassroomController::class, 'join'])->name('classrooms.join');

// Assignment Routes
Route::post('/classrooms/{classroom}/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
Route::get('/assignments/{assignment}/download', [AssignmentController::class, 'download'])->name('assignments.download');

// Submission Routes
Route::post('/assignments/{assignment}/submit', [SubmissionController::class, 'store'])->name('submissions.store');
Route::post('/submissions/{submission}/grade', [SubmissionController::class, 'grade'])->name('submissions.grade');
Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy'])->name('submissions.destroy');
Route::get('/submissions/{submission}/download', [SubmissionController::class, 'download'])->name('submissions.download');

// Comment Routes
Route::post('/assignments/{assignment}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/history', [AdminHistoryController::class, 'index'])->name('admin.history.index');
});
