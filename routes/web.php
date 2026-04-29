<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;

Route::view('/', 'welcome')->name('home');


// ========== PUBLIC TEACHER ROUTES (No auth required) ==========
Route::prefix('teacher')
    ->middleware(['guest:teacher'])
    ->group(function () {
    Route::get('/register', function () {
        return view('teacher.register');
    })->name('teacher.register');
    Route::post('/register', [UserController::class, 'register'])->name('teacher.register.store');

    Route::get('/login', function () {
        return view('teacher.login');
    })->name('teacher.login');
    Route::post('/login', [UserController::class, 'login'])->name('teacher.login.store');
});

// ========== PUBLIC STUDENT ROUTES (No auth required) ==========
Route::prefix('student')
    ->middleware(['guest:student'])
    ->group(function () {
    Route::get('/register', function () {
        return view('students.register');
    })->name('student.register');
    Route::post('/register', [StudentController::class, 'register'])->name('student.register.store');

    Route::get('/login', function () {
        return view('students.login');
    })->name('student.login');
    Route::post('/login', [StudentController::class, 'login'])->name('student.login.store');
});

// ========== PROTECTED TEACHER ROUTES (Auth required) ==========
Route::prefix('teacher')
    ->middleware(['auth:teacher', 'no.store'])
    ->group(function () {
        Route::get('/results', [ResultController::class, 'index'])
            ->name('teacher.results.index');
        Route::post('/results', [ResultController::class, 'store'])
            ->name('teacher.results.store');

        Route::patch('/results/{student_id}/publish', [ResultController::class, 'publish'])
            ->name('teacher.results.publish');
        Route::get('/results/{student_id}', [ResultController::class, 'show'])
            ->name('teacher.results.show');
        Route::put('/results/{result}', [ResultController::class, 'update'])
            ->name('teacher.results.update');
        Route::delete('/results/{result}', [ResultController::class, 'destroy'])
            ->name('teacher.results.destroy');

        Route::post('/logout', [UserController::class, 'logout'])->name('teacher.logout');

        Route::get('/subjects', [SubjectController::class, 'index'])
            ->name('teacher.subjects.index');
        Route::post('/subjects', [SubjectController::class, 'store'])
            ->name('teacher.subjects.store');
        Route::put('/subjects/{subject}', [SubjectController::class, 'update'])
            ->name('teacher.subjects.update');
        Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])
            ->name('teacher.subjects.destroy');
    });

// ========== PROTECTED STUDENT ROUTES (Student auth required) ==========
Route::prefix('student')
    ->middleware(['auth:student', 'no.store'])
    ->group(function () {
        Route::get('/', [StudentController::class, 'index'])
            ->name('student.index');
        Route::get('/results', [StudentController::class, 'show'])
            ->name('student.results.current');
        Route::get('/results/{student_id}', [StudentController::class, 'show'])
            ->name('student.results.show');
        Route::get('/profile/edit', [StudentController::class, 'edit'])
            ->name('student.profile.edit');
        Route::put('/profile', [StudentController::class, 'update'])
            ->name('student.profile.update');

        Route::post('/logout', [StudentController::class, 'logout'])->name('student.logout');
    });
    
