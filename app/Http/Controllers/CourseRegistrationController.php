<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\CourseUser;
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

        $this->validateCourseRegistration($user, $course);

        try {
            DB::transaction(function () use ($user, $course) {
                if ($user->courses()->where('course_id', $course->id)->exists()) {
                    $user->courses()->updateExistingPivot($course->id, [
                        'status' => 'signed_up',
                        'signed_up_at' => now()
                    ]);
                } else {
                    $user->courses()->attach($course->id, [
                        'status' => 'signed_up',
                        'signed_up_at' => now()
                    ]);
                }
            });
    
            return redirect()->back()->with('success', 'Du hast dich eingetragen. Der J&S Coach wird per E-Mail informiert.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Es gab einen Fehler beim Eintragen in diesen Kurs.');
        }
    }

    public function register(Request $request, Course $course, User $user)
    {
        $currUser = auth()->user();
        if (!$currUser->isJSCoach()) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um diesen Status zu ändern.');
        }
        try {
            DB::transaction(function () use ($user, $course) {
                if ($user->courses()->where('course_id', $course->id)->exists()) {
                    $user->courses()->updateExistingPivot($course->id, [
                        'status' => 'registered',
                        'registered_at' => now()
                    ]);
                } else {
                    $user->courses()->attach($course->id, [
                        'status' => 'registered',
                        'registered_at' => now()
                    ]);
                }
            });
    
            return redirect()->back()->with('success', $user->name . ' wurde für den Kurs angemeldet.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Es gab einen Fehler beim Anmelden dieses Kurses.');
        }
    }

    public function changeStatus(Request $request, Course $course)
    {

        $currUser = auth()->user();
        $currentTeam = $currUser->currentTeam;
        if (!$currUser->isJSVerantwortlich() || !$currentTeam) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um diesen Status zu ändern.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:signed_up,registered,attended,cancelled',
        ]);
        $user = User::findOrFail($validated['user_id']);
        if (!$currUser->isJsCoach() && !$currentTeam->users->contains($user)) {
            return redirect()->back()->with('error', 'Der Benutzer gehört nicht zum aktuellen Team.');
        }

        if (!$currUser->isJsCoach() && $validated['status'] == 'registered') {
            return redirect()->back()->with('error', 'Nur ein J&S Coach kann einen Leiter anmelden.');
        }

        try {
            DB::transaction(function () use ($user, $course, $validated) {
                if ($user->courses()->where('course_id', $course->id)->exists()) {
                    $user->courses()->updateExistingPivot($course->id, [
                        'status' => $validated['status'],
                        CourseUser::getTimestampField($validated['status']) => now(),
                    ]);
                } else {
                    $user->courses()->attach($course->id, [
                        'status' => $validated['status'],
                        CourseUser::getTimestampField($validated['status']) => now(),
                    ]);
                }
            });

            return redirect()->back()->with('success', "{$user->name} wurde für den Kurs aktualisiert.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Es gab einen Fehler.');
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
        $validityDate = auth()->user()->getCourseRevalidationDate();

        $courses = Course::with('courseType')
            ->availableToCurrentTeam()
            ->passesAgeRequirement()
            ->registrationDeadlineNotPassed()
            ->fullfillsCourseTypePrerequisite()
            ->withUserStatus()
            ->get();

        $lastAttended = collect([auth()->user()->getCoursesByStatus('attended')->last()])->filter();
        return view('courses.available', compact('courses', 'lastAttended', 'validityDate'));
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
