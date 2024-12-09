<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with('courseType')->get();
        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courseTypes = CourseType::all();
        return view('courses.create', compact('courseTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_nr' => 'required|string|unique:courses|max:255',
            'name' => 'required|string|max:255',
            'course_type_id' => 'required|exists:course_types,id',
            'location' => 'required|string|max:255',
            'date_start' => 'required|date|before_or_equal:date_end',
            'date_end' => 'required|date|after_or_equal:date_start',
            'prerequisites' => 'nullable|string|max:255',
            'registration_deadline' => 'required|date|before:date_start',
            'notes' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        Course::create($request->all());

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $user = auth()->user();
        $users = $course->users;

        $isRegistered = $course->users->contains($user);

        return view('courses.show', compact('course', 'users', 'isRegistered'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $courseTypes = CourseType::all(); // Get all course types for the dropdown
        return view('courses.edit', compact('course', 'courseTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_nr' => 'required|string|unique:courses,course_nr,' . $course->id . '|max:255',
            'name' => 'required|string|max:255',
            'course_type_id' => 'required|exists:course_types,id',
            'location' => 'required|string|max:255',
            'date_start' => 'required|date|before_or_equal:date_end',
            'date_end' => 'required|date|after_or_equal:date_start',
            'prerequisites' => 'nullable|string|max:255',
            'registration_deadline' => 'required|date|before:date_start',
            'notes' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        $course->update($request->all());

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function registerForCourse(Course $course)
    {
        $user = auth()->user();

        if ($course->registration_deadline >= now()) {
            $course->users()->attach($user);
            return back()->with('success', 'Sie haben sich erfolgreich für den Kurs angemeldet.');
        }

        return back()->with('error', 'Die Registrierungsfrist für diesen Kurs ist bereits abgelaufen.');
    }

    public function unregisterFromCourse(Course $course)
    {
        $user = auth()->user();

        if ($course->users->contains($user)) {
            $course->users()->detach($user);
            return back()->with('success', 'Sie haben sich erfolgreich vom Kurs abgemeldet.');
        }

        return back()->with('error', 'Sie sind nicht für diesen Kurs registriert.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }

    public function userCourses()
    {
        $user = auth()->user();
        $courses = $user->courses()->orderBy('date_start')->get();

        return view('courses.user-courses', compact('courses'));
    }

}
