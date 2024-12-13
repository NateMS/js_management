<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Validation\Rule;
use App\Models\CourseType;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function availableCourses()
    {
        $validityDate = auth()->user()->getCourseRevalidationDate();

        $courses = Course::with('courseType')
            ->availableToCurrentTeam()
            ->passesAgeRequirement()
            ->registrationDeadlineNotPassed()
            ->fullfillsCourseTypePrerequisite()
            ->withoutUser()
            ->get();

        $lastAttended = auth()->user()->getCoursesByStatus('attended')->last() ?? null;
        $plannedCourses = auth()->user()
            ->courses()
            ->futureCourses()
            ->whereIn('course_user.status', ['signed_up', 'registered'])
            ->get();
        $pastCourses = auth()->user()
            ->courses()
            ->pastCourses()
            ->whereIn('course_user.status', ['signed_up', 'registered'])
            ->get();
        return view('courses.available', compact('courses', 'lastAttended', 'validityDate', 'plannedCourses', 'pastCourses'));
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

    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->isJSVerantwortlich()) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um die Kursverwaltung anzuschauen.');
        }
        $years = \App\Models\Course::query()
            ->selectRaw('strftime("%Y", date_start) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $selectedYear = $years ? $request->input('year', $years->first()) : '';
        

        $courses = $selectedYear ? Course::whereRaw('strftime("%Y", date_start) = ?', [$selectedYear])->with('courseType')->get() : Course::with('courseType')->get();
        return view('courses.index', compact('courses', 'years', 'selectedYear'));
    }

    public function create()
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;
        if (!$user->isJSVerantwortlich()) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um einen Kurs zu erstellen.');
        }
        if ($user->isJsCoach()) {
            $courseTypes = CourseType::all();
        } else {
            $courseTypes = CourseType::whereHas('teams', function ($query) use ($currentTeam) {
                $query->where('teams.id', $currentTeam->id);
            })->get();
        }
        return view('courses.create', compact('courseTypes'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;
        if (!$user->isJSVerantwortlich()) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um einen Kurs zu erstellen.');
        }

        $courseTypeIds = CourseType::whereHas('teams', function ($query) use ($currentTeam) {
            $query->where('teams.id', $currentTeam->id);
        })->pluck('id')->toArray();

        $request->validate([
            'course_nr' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'course_type_id' => [
                'required',
                'exists:course_types,id',
                Rule::in($user->isJSCoach() ? CourseType::all()->pluck('id')->toArray() : $courseTypeIds),
            ],
            'location' => 'required|string|max:255',
            'date_start' => 'required|date|before_or_equal:date_end',
            'date_end' => 'required|date|after_or_equal:date_start',
            'prerequisites' => 'nullable|string|max:255',
            'registration_deadline' => 'required|date|before:date_start',
            'notes' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        Course::create($request->all());

        return redirect()->route('courses.index')->with('success', 'Kurs erstellt.');
    }

    public function show(Course $course)
    {
        
        $user = auth()->user();
        
        if (!$user->canAccessCourse($course)) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um diesen Kurs anzuschauen.');    
        }

        $users = $course->users;

        $userStatus = $course->users()
            ->where('user_id', $user->id)
            ->first()?->pivot->status;

        $currentTeamUsers = $user->currentTeam->users;

        $availableUsers = $course->availableUsers();

        return view('courses.show', compact('course', 'users', 'userStatus', 'availableUsers', 'currentTeamUsers'));
    }

    public function edit(Course $course)
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;
        if (!$user->canEditCourse($course)) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um diesen Kurs zu bearbeiten.');
        }

        if ($user->isJsCoach()) {
            $courseTypes = CourseType::all();
        } else {
            $courseTypes = CourseType::whereHas('teams', function ($query) use ($currentTeam) {
                $query->where('teams.id', $currentTeam->id);
            })->get();
        }
        return view('courses.edit', compact('course', 'courseTypes'));
    }

    public function update(Request $request, Course $course)
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;
        if (!$user->canEditCourse($course)) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um diesen Kurs zu bearbeiten.');
        }

        $courseTypeIds = CourseType::whereHas('teams', function ($query) use ($currentTeam) {
            $query->where('teams.id', $currentTeam->id);
        })->pluck('id')->toArray();

        $request->validate([
            'course_nr' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'course_type_id' => [
                'required',
                'exists:course_types,id',
                Rule::in($user->isJSCoach() ? CourseType::all()->pluck('id')->toArray() : $courseTypeIds),
            ],
            'location' => 'required|string|max:255',
            'date_start' => 'required|date|before_or_equal:date_end',
            'date_end' => 'required|date|after_or_equal:date_start',
            'prerequisites' => 'nullable|string|max:255',
            'registration_deadline' => 'required|date|before:date_start',
            'notes' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        $course->update($request->all());

        return redirect()->route('courses.show', $course)->with('success', 'Kursänderungen gespeichert.');
    }

    public function listSignedUpUsers()
    {
        $courses = Course::whereHas('users', function ($query) {
            $query->where('course_user.status', 'signed_up');
        })
        ->with(['users' => function ($query) {
            $query->where('course_user.status', 'signed_up');
        }])
        ->get();

        return view('courses.signed_up', compact('courses'));
    }


    public function destroy(Course $course)
    {
        if (auth()->user()->isJSCoach()) {
            $course->delete();
            return redirect()->route('courses.index')->with('success', 'Kurs gelöscht.');
        }        

        return redirect()->route('courses.index')->with('error', 'Du hast keine Berechtigung, um diesen Kurs zu löschen.');
    }
}
