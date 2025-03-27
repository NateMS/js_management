<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DeploymentController;
use App\Http\Controllers\CourseRegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserHasTeam;
use App\Http\Middleware\CheckCourseAccess;
// use Illuminate\Foundation\Auth\EmailVerificationRequest;
// use Illuminate\Http\Request;
  
Route::get('/notify-courses', [NotificationController::class, 'NotifyCourseParticipants']);
Route::get('/confirm-attendance/{token}', [NotificationController::class, 'confirm'])->name('email.confirm');
Route::get('/cancel-attendance/{token}', [NotificationController::class, 'cancel'])->name('email.cancel');
Route::post('/deploy', [DeploymentController::class, 'deploy'])->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [CourseController::class, 'availableCourses'])->name('home');

    Route::middleware([
        EnsureUserHasTeam::class,
    ])->group(function () {
        Route::get('/courses/my-courses', [CourseController::class, 'myCourses'])->name('courses.my-courses');
        Route::get('/courses/signed-up', [CourseController::class, 'listSignedUpUsers'])->name('courses.signed_up');
        Route::get('/courses/waiting-list', [CourseController::class, 'listWaitingListUsers'])->name('courses.waiting_list');
        Route::get('/courses/registered', [CourseController::class, 'listRegisteredUsers'])->name('courses.registered');
        Route::get('/courses/attended', [CourseController::class, 'listAttendedUsers'])->name('courses.attended');
        Route::get('/courses/cancelled', [CourseController::class, 'listCancelledUsers'])->name('courses.cancelled');
        Route::get('/courses/all', [CourseController::class, 'listAllUsers'])->name('courses.all');

        Route::middleware([CheckCourseAccess::class])->group(function() {
            Route::resource('courses', CourseController::class);
            Route::post('/courses/{course}/signup/{user}', [CourseRegistrationController::class, 'signUp'])->name('courses.signup');
            Route::post('/courses/{course}/attend/{user}', [CourseRegistrationController::class, 'attend'])->name('courses.attend');
            Route::post('/courses/{course}/cancel/{user}', [CourseRegistrationController::class, 'cancel'])->name('courses.cancel');
            Route::post('/courses/{course}/register/{user}', [CourseRegistrationController::class, 'register'])->name('courses.register');
            Route::post('/courses/{course}/change-status', [CourseRegistrationController::class, 'changeStatus'])->name('courses.change-status');
            Route::post('/courses/{course}/delete-status', [CourseRegistrationController::class, 'deleteStatus'])->name('courses.delete-status');
        });
        Route::resource('course-types', CourseTypeController::class);
        Route::resource('users', UserController::class);
    });
});

// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();
 
//     return redirect('/home');
// })->middleware(['auth', 'signed'])->name('verification.verify');

// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
 
//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');