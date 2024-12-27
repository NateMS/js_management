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
        $user = auth()->user();
        if ($user->allTeams()->count() > 0) {
            if ($user->current_team_id == null) {
                $user->current_team_id = $user->allTeams()->first()->id;
                $user->save();
            }
        } else {
            return view('welcome');
        }

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
            abort(404);
        }
        $years = Course::query()
            ->selectRaw('YEAR(date_start) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $selectedYear = $years ? $request->input('year', $years->first()) : '';
        

        $courses = $selectedYear ? Course::whereRaw('YEAR(date_start) = ?', [$selectedYear])->with('courseType')->get() : Course::with('courseType')->get();
        return view('courses.index', compact('courses', 'years', 'selectedYear'));
    }

    public function create()
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;
        if (!$user->isJSVerantwortlich()) {
            abort(404);
        }
        if ($user->isJsCoach()) {
            $courseTypes = CourseType::all();
        } else {
            $courseTypes = CourseType::whereHas('teams', function ($query) use ($currentTeam) {
                $query->where('teams.id', $currentTeam->id);
            })->get();
        }

        $title = "Kurs erfassen";
        $buttonTitle = "Kurs erstellen";
        $submitUrl = route('courses.store');
        $backUrl = route('courses.index');
        $course = new Course();
        $method = 'POST';
        return view('courses.form', compact('courseTypes', 'title', 'buttonTitle', 'submitUrl', 'backUrl', 'course', 'method'));
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
            abort(404);
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
            abort(404);
        }

        if ($user->isJsCoach()) {
            $courseTypes = CourseType::all();
        } else {
            $courseTypes = CourseType::whereHas('teams', function ($query) use ($currentTeam) {
                $query->where('teams.id', $currentTeam->id);
            })->get();
        }

        $title = "Kurs bearbeiten";
        $buttonTitle = "Änderungen speichern";
        $submitUrl = route('courses.update', $course);
        $backUrl = route('courses.show', $course);
        $method = 'PUT';
        return view('courses.form', compact('course', 'courseTypes', 'title', 'buttonTitle', 'submitUrl', 'backUrl', 'method'));
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
        $user = auth()->user();
        $currentTeam = $user->currentTeam;

        if (!$user->isJSVerantwortlich()) {
            abort(404);
        }
        $courses = Course::whereHas('users', function ($query) {
            $query->where('course_user.status', 'signed_up');
        })
        ->with(['users' => function ($query) {
            $query->where('course_user.status', 'signed_up');
        }])
        ->orderBy('date_start', 'asc')
        ->get();

        $title = 'Eingetragene Kurse';
        return view('courses.by_status', compact('courses', 'title'));
    }

    public function listRegisteredUsers()
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;

        if (!$user->isJSVerantwortlich()) {
            abort(404);
        }
        $courses = Course::whereHas('users', function ($query) {
            $query->where('course_user.status', 'registered');
        })
        ->with(['users' => function ($query) {
            $query->where('course_user.status', 'registered');
        }])
        ->orderBy('date_start', 'asc')
        ->get();

        $title = 'Angemeldete Kurse';
        return view('courses.by_status', compact('courses', 'title'));
    }

    public function listAttendedUsers(Request $request)
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;

        if (!$user->isJSVerantwortlich()) {
            abort(404);
        }
        $years = Course::whereHas('users', function ($query) {
            $query->where('course_user.status', 'attended');
        })
        ->selectRaw('YEAR(date_start) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');
        
        $selectedYear = $years ? $request->input('year', $years->first()) : '';

        $courseQuery = Course::whereHas('users', function ($query) {
            $query->where('course_user.status', 'attended');
        })
        ->with(['users' => function ($query) {
            $query->where('course_user.status', 'attended');
        }]);
    
        if ($selectedYear) {
            $courseQuery->whereRaw('YEAR(date_start) = ?', [$selectedYear]);
        }
    
        $courses = $courseQuery->orderBy('date_start', 'asc')->get();

        $title = 'Teilgenommene Kurse';

        return view('courses.by_status', compact('courses', 'title', 'years', 'selectedYear'));
    }

    public function listCancelledUsers(Request $request)
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;

        if (!$user->isJSVerantwortlich()) {
            abort(404);
        }
        $years = Course::whereHas('users', function ($query) {
            $query->where('course_user.status', 'cancelled');
        })
        ->selectRaw('YEAR(date_start) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');
        
        $selectedYear = $years ? $request->input('year', $years->first()) : '';

        $courseQuery = Course::whereHas('users', function ($query) {
            $query->where('course_user.status', 'cancelled');
        })
        ->with(['users' => function ($query) {
            $query->where('course_user.status', 'cancelled');
        }]);
    
        if ($selectedYear) {
            $courseQuery->whereRaw('YEAR(date_start) = ?', [$selectedYear]);
        }
    
        $courses = $courseQuery->orderBy('date_start', 'asc')->get();

        $title = 'Abgesagte Kurse';
        return view('courses.by_status', compact('courses', 'title', 'years', 'selectedYear'));
    }

    public function listAllUsers(Request $request)
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;

        if (!$user->isJSVerantwortlich()) {
            abort(404);
        }
        $years = Course::whereHas('users')
        ->selectRaw('YEAR(date_start) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');
        
        $selectedYear = $years ? $request->input('year', $years->first()) : '';

        $courseQuery = Course::whereHas('users')
        ->with(['users']);
    
        if ($selectedYear) {
            $courseQuery->whereRaw('YEAR(date_start) = ?', [$selectedYear]);
        }
    
        $courses = $courseQuery->orderBy('date_start', 'asc')->get();

        $title = 'Alle Teilnehmer';
        return view('courses.by_status', compact('courses', 'title', 'years', 'selectedYear'));
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
