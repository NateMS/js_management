<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseTypeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseRegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserHasTeam;
use App\Http\Middleware\CheckCourseAccess;
use Illuminate\Support\Facades\Artisan;

Route::post('/deploy', function () {
    if (request('key') !== env('DEPLOY_KEY')) {
        abort(403, 'Unauthorized');
    }

    $firstTimeMigration = !Schema::hasTable('users');

    try {
        Artisan::call('migrate');

        if ($firstTimeMigration) {
            Artisan::call('db:seed --force');
        }
    } catch (Exception $e) {
        return response()->json(['message' => $e], 500);
    }
   

    return response()->json(['message' => 'Deployment complete!'], 200);
})->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);;


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
        Route::get('/courses/registered', [CourseController::class, 'listRegisteredUsers'])->name('courses.registered');
        Route::get('/courses/attended', [CourseController::class, 'listAttendedUsers'])->name('courses.attended');
        Route::get('/courses/cancelled', [CourseController::class, 'listCancelledUsers'])->name('courses.cancelled');

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
        
        Route::get('/team/users', [UserController::class, 'teamUsers'])->name('team.users');
        Route::get('/team/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::post('/team/users/{user}', [UserController::class, 'addJSNumber'])->name('users.add_js_number');
    
    
        //Route::get('/courses/available', [CourseRegistrationController::class, 'index'])->name('courses.available');
        //Route::post('/courses/{course}/register', [CourseRegistrationController::class, 'register'])->name('courses.register');
        //Route::delete('/courses/{course}/unregister', [CourseRegistrationController::class, 'unregister'])->name('courses.unregister');
    });
});

