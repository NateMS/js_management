<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    public function teamUsers()
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
                ->whereIn('course_user.status', ['signed_up', 'registered'])
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
                ->whereIn('course_user.status', ['signed_up', 'registered'])
                ->get();
            $past = $user
                ->courses()
                ->notHidden()
                ->pastCourses()
                ->get();
        }
        return view('users.show', compact('user', 'validityDate', 'planned', 'past'));
    }

    public function addJSNumber(Request $request, User $user)
    {
        $currUser = auth()->user();
        $currentTeam = $currUser->currentTeam;
        if (!$currUser->isJSCoach() && !($currUser->isJSVerantwortlich() && $currentTeam->users->contains($user)) && $currUser->id != $user->id) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, diesen Benutzer zu bearbeiten.');
        }

        $request->validate([
            'js_number' => 'nullable|string|max:255',
        ]);

        if ($request->get('js_number') == $user->js_number) {
            return redirect()->route('users.show', $user);
        }

        $user->update($request->all());

        
        return redirect()->route('users.show', $user)->with('success', 'J&S Nummer angepasst!');
    }
}
