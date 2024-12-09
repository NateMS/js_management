<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseRegistrationController extends Controller
{
    /**
     * Display a listing of available courses for registration.
     */
    public function index()
    {
        $courses = Course::with('courseType')
        ->where('registration_deadline', '>=', now())
        ->orderBy('courseType.order')
        ->get();

        return view('course-registrations.index', compact('courses'));
    }

    public function signUp(Request $request, Course $course)
    {
        $user = auth()->user();

        // Validate course registration
        $this->validateCourseRegistration($user, $course);

        try {
            DB::transaction(function () use ($user, $course) {
                // Attach user to course
                $user->courses()->attach($course->id, [
                    'status' => 'signed_up',
                    'signed_up_at' => now()
                ]);
            });

            return redirect()->back()->with('success', 'Du hat dich eingetragen. Der / Die J&S Coach/in wird per E-Mail informiert.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Es gab einen Fehler beim eintragen in diesen Kurs');
        }
    }

    public function cancel(Request $request, Course $course)
    {
        $user = auth()->user();

        try {
            DB::transaction(function () use ($user, $course) {
                // Find the pivot record
                $pivotRecord = DB::table('course_user')
                    ->where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->where('status', 'signed_up')
                    ->first();

                if ($pivotRecord) {
                    // Update status to cancelled
                    DB::table('course_user')
                        ->where('user_id', $user->id)
                        ->where('course_id', $course->id)
                        ->update([
                            'status' => 'cancelled',
                            'cancelled_at' => now()
                        ]);
                }
            });

            return redirect()->back()->with('success', 'Du hast dich für diesen Kurs ausgetragen.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Es gab einen Fehler beim Austragen für diesen Kurs.');
        }
    }

    protected function validateCourseRegistration(User $user, Course $course)
    {
        // Check if registration is open
        if (!$course->isRegistrationOpen()) {
            throw new \Exception('Registration is closed for this course.');
        }

        // Check if user is already registered
        $existingRegistration = DB::table('course_user')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['signed_up', 'registered', 'attended'])
            ->exists();

        if ($existingRegistration) {
            throw new \Exception('You are already registered for this course.');
        }
    }

    public function availableCourses()
    {
        $courses = Course::with('courseType')
            ->availableToCurrentTeam()
            ->passesAgeRequirement()
            ->registrationDeadlineNotPassed()
            ->fullfillsCourseTypePrerequisite()
            ->withUserStatus()
            ->get();

        $lastAttended = collect([auth()->user()->getCoursesByStatus('attended')->last()])->filter();
        return view('courses.available', compact('courses', 'lastAttended'));
    }

    public function myCourses()
    {
        $user = auth()->user();
        
        $signedUpCourses = $user->getCoursesByStatus('signed_up');
        $registeredCourses = $user->getCoursesByStatus('registered');
        $attendedCourses = $user->getCoursesByStatus('attended');
        $cancelledCourses = $user->getCoursesByStatus('cancelled');

        return view('courses.mycourses', compact(
            'signedUpCourses', 
            'registeredCourses', 
            'attendedCourses', 
            'cancelledCourses'
        ));
    }
}
