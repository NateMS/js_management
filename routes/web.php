<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseRegistrationController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('auth/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [CourseRegistrationController::class, 'availableCourses'])->name('home');

    Route::resource('course-types', CourseTypeController::class);
    Route::get('/courses/available', [CourseRegistrationController::class, 'availableCourses'])->name('courses.available');
    Route::get('/courses/my-courses', [CourseRegistrationController::class, 'myCourses'])->name('courses.my-courses');
    Route::post('/courses/{course}/signup', [CourseRegistrationController::class, 'signUp'])->name('courses.signup');
    Route::post('/courses/{course}/cancel', [CourseRegistrationController::class, 'cancel'])->name('courses.cancel');
    
    Route::resource('courses', CourseController::class);

    
    Route::get('/team/users', [UserController::class, 'teamUsers'])->name('team.users');
    //Route::get('/courses/available', [CourseRegistrationController::class, 'index'])->name('courses.available');
    //Route::post('/courses/{course}/register', [CourseRegistrationController::class, 'register'])->name('courses.register');
    //Route::delete('/courses/{course}/unregister', [CourseRegistrationController::class, 'unregister'])->name('courses.unregister');

    //Route::get('my-courses', [CourseController::class, 'userCourses'])->name('courses.user-courses');

    
});