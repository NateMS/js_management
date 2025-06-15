<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Laravel\Jetstream\Jetstream;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $currentTeam = auth()->user()->currentTeam;

        if (!$currentTeam) {
            redirect()->back();
        }

        $users = $currentTeam->users()->aged18OrOlder()->excludeOwners(auth()->user()->currentTeam)->get();
        $under18 = $currentTeam->users()->agedUnder18()->get();
        $past = collect([]);
        $soon = collect([]);
        $future = collect([]);
        $none = collect([]);

        foreach ($users as $user) {
            $revalidationDate = $user->getCourseRevalidationDate();

            if (!$revalidationDate) {
                $none->push($user);
                continue;
            }

            $revalidationDate = Carbon::createFromFormat('d.m.Y', $revalidationDate);

            if ($revalidationDate->isPast()) {
                $past->push($user);
                continue;
            }
            
            if ($revalidationDate->isBetween(now(), now()->addMonths(18))) {
                $soon->push($user);
            } else {
                $future->push($user);
            }
        }

        $past = $past->sortBy('birthdate');
        $soon = $soon->sortBy('birthdate');
        $future = $future->sortBy('birthdate');
        $none = $none->sortBy('birthdate');
        $under18 = $under18->sortBy('birthdate');

        return view('users.team', compact('past', 'soon', 'future', 'none', 'under18'));
    }

    public function show(User $user)
    {
        $currUser = auth()->user();
        $currentTeam = $currUser->currentTeam;
        if (!$currUser->isJSCoach() && !$currentTeam->users->contains($user)) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, diesen Benutzer anzuschauen.');
        }

        $validityDate = $user->getCourseRevalidationDate();

        if ($currUser->isJSVerantwortlich()) {
            $planned = $user
                ->courses()
                ->futureCourses()
                ->whereIn('course_user.status', ['signed_up', 'registered', 'waiting_list'])
                ->get();
            $past = $user
                ->courses()
                ->pastCourses()
                ->get();
        } else {
            $planned = $user
                ->courses()
                ->notHidden()
                ->futureCourses()
                ->whereIn('course_user.status', ['signed_up', 'registered', 'waiting_list'])
                ->get();
            $past = $user
                ->courses()
                ->notHidden()
                ->pastCourses()
                ->get();
        }
        return view('users.show', compact('user', 'validityDate', 'planned', 'past'));
    }

    public function create()
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;
        if (!$user->isJSVerantwortlich()) {
            abort(404);
        }

        $title = "Neue Person erfassen";
        $buttonTitle = "speichern";
        $submitUrl = route('users.store');
        $backUrl = route('users.index');
        $user = new User();
        $method = 'POST';
        return view('users.form', compact('title', 'buttonTitle', 'submitUrl', 'backUrl', 'user', 'method'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $currentTeam = $user->currentTeam;
        if (!$user->isJSVerantwortlich()) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um einen neuen Leiter zu erfassen.');
        }

        $roleKeys = collect(Jetstream::$roles)->pluck('key')->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'js_number' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'password' => 'required|string|max:255',
            'role' => ['required', Rule::in($roleKeys)],
        ]);

        $data = $request->merge([
            'password' => Hash::make($request->get('password')),
        ])->all();

        $user2 = User::create($request->all());

        $user2->teams()->attach($currentTeam->id, ['role' => $request->get('role')]);

        return redirect()->route('users.index')->with('success',  "$user2->name wurde erstellt und dem Team hinzugefügt.");
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->canEditUser($user)) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, diesen Benutzer zu bearbeiten.');
        }

        $currentTeam = $user->currentTeam;
        $roleKeys = collect(Jetstream::$roles)->pluck('key')->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users,email,'. $user->id.',id',
            'js_number' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'role' => ['required', Rule::in($roleKeys)],
        ]);

        $user->update($request->all());

        $user->teams()->updateExistingPivot($currentTeam->id, ['role' => $request->get('role')]);
        
        return redirect()->route('users.show', $user)->with('success', 'Benutzer angepasst!');
    }

    public function edit(User $user)
    {
        if (!auth()->user()->canEditUser($user)) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, diesen Benutzer zu bearbeiten.');
        }

        $title = "$user->name bearbeiten";
        $buttonTitle = "speichern";
        $submitUrl = route('users.update', $user);
        $backUrl = route('users.show', $user);
        $method = 'PUT';
        return view('users.form', compact('user', 'title', 'buttonTitle', 'submitUrl', 'backUrl', 'method'));
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->canEditUser($user)) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, diesen Benutzer zu bearbeiten.');
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', $user->name . ' entfernt.');
    }
}
