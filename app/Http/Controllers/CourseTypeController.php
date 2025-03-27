<?php

namespace App\Http\Controllers;

use App\Models\CourseType;
use Illuminate\Http\Request;
use App\Models\Team;

class CourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courseTypes = CourseType::orderBy('order')->get();
        return view('course-types.index', compact('courseTypes'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = Team::all();
        $courseTypes = CourseType::all();
        $title = "Kurstyp erfassen";
        $buttonTitle = "Kurstyp erstellen";
        $submitUrl = route('course-types.store');
        $courseType = new CourseType();
        $method = "POST";
        return view('course-types.form', compact('teams', 'courseTypes', 'title', 'buttonTitle', 'submitUrl', 'courseType', 'method'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'requires_repetition' => $request->has('requires_repetition') ? true : false,
            'can_only_attend_once' => $request->has('can_only_attend_once') ? true : false,
            'is_kids_course' => $request->has('is_kids_course') ? true : false,

        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'minimum_age' => 'nullable|integer|min:0',
            'maximum_age' => 'nullable|integer|min:0|gte:minimum_age',
            'requires_repetition' => 'nullable|boolean',
            'can_only_attend_once' => 'nullable|boolean',
            'is_kids_course' => 'nullable|boolean',
            'prerequisite_course_type_id' => 'nullable|exists:course_types,id',
            'teams' => 'nullable|array',
            'teams.*' => 'exists:teams,id',
        ]);


        $courseType = CourseType::create($validated);
        $courseType->teams()->sync($validated['teams'] ?? []);

        return redirect()->route('course-types.index')->with('success', 'Kurstyp erfolgreich erstellt.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseType $courseType)
    {
        $teams = Team::all();
        $courseTypes = CourseType::all();
        $title = "Kurstyp bearbeiten";
        $buttonTitle = "Änderungen speichern";
        $submitUrl = route('course-types.update', $courseType);
        $method = "PUT";
        return view('course-types.form', compact('courseType', 'teams', 'courseTypes', 'title', 'buttonTitle', 'submitUrl', 'method'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseType $courseType)
    {
        $request->merge([
            'requires_repetition' => $request->has('requires_repetition') ? true : false,
            'can_only_attend_once' => $request->has('can_only_attend_once') ? true : false,
            'is_kids_course' => $request->has('is_kids_course') ? true : false,
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'required|integer|min:0',
            'minimum_age' => 'nullable|integer|min:0',
            'maximum_age' => 'nullable|integer|min:0|gte:minimum_age',
            'requires_repetition' => 'nullable|boolean',
            'can_only_attend_once' => 'nullable|boolean',
            'is_kids_course' => 'nullable|boolean',
            'prerequisite_course_type_id' => 'nullable|exists:course_types,id',
            'teams' => 'nullable|array',
            'teams.*' => 'exists:teams,id',
        ]);
        
        $courseType->update($validated);
        $courseType->teams()->sync($validated['teams'] ?? []);

        return redirect()->route('course-types.index')->with('success', 'Änderungen gespeichert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseType $courseType)
    {
        $courseType->delete();

        return redirect()->route('course-types.index')->with('success', 'Kurstyp gelöscht.');
    }
}
