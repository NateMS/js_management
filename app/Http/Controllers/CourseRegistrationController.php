<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\CourseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewSignUpEmail;

class CourseRegistrationController extends Controller
{
    public function signUp(Request $request, Course $course, User $user)
    {
        if (!$course->isRegistrationOpen()) {
            throw new \Exception('Registration is closed for this course.');
        }
        $existingRegistration = DB::table('course_user')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', ['signed_up', 'registered', 'attended'])
            ->exists();

        if ($existingRegistration) {
            throw new \Exception('You are already registered for this course.');
        }

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

            if (!auth()->user()->isJSCoach()) {
                Mail::to(User::getJSCoachMail())->send(new NewSignUpEmail($course, $user));
            }
    
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

        if ($course->isInPast()) {
            return redirect()->back()->with('error', 'Du kannst keine Anmeldung für Kurse in der Vergangenheit durchführen.');
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

    public function attend(Request $request, Course $course, User $user)
    {
        $currUser = auth()->user();
        $currentTeam = $currUser->currentTeam;
        if (!$currUser->isJSCoach() && !($currUser->isJSVerantwortlich() && $currentTeam->users->contains($user)) && $currUser->id != $user->id) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um diesen Status zu ändern.');
        }

        if (!$course->isInPast()) {
            return redirect()->back()->with('error', 'Du kannst nicht an einem Kurs teilgenommen haben, wenn der Kurs noch nicht abgeschlossen ist.');
        }
        try {
            DB::transaction(function () use ($user, $course) {
                if ($user->courses()->where('course_id', $course->id)->exists()) {
                    $user->courses()->updateExistingPivot($course->id, [
                        'status' => 'attended',
                        'completed_at' => now()
                    ]);
                } else {
                    $user->courses()->attach($course->id, [
                        'status' => 'attended',
                        'completed_at' => now()
                    ]);
                }
            });
    
            return redirect()->back()->with('success', $currUser->id != $user->id ? $user->name . ' hat an diesem Kurs teilgenommen.' : 'Du hast die Teilnahme für diesen Kurs bestätigt!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten');
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
        if (!$currUser->isJsCoach()) {
            if (!$currentTeam->users->contains($user)) {
                return redirect()->back()->with('error', 'Der Benutzer gehört nicht zum aktuellen Team.');
            }

            if ($validated['status'] == 'registered') {
                return redirect()->back()->with('error', 'Nur ein J&S Coach kann einen Leiter anmelden.');
            }

            if (!$course->isInPast() && $course->userStatus($validated['user_id'])?->status == 'registered') {
                return redirect()->back()->with('error', 'Nur ein J&S Coach kann diesen Status ändern.');
            }
        }
        

        if ($validated['status'] == 'attended' && !$course->isInPast()) {
            return redirect()->back()->with('error', 'Teilnahme kann nicht vor Kursbeginn bestätigt werden.');
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
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten.');
        }
    }

    public function cancel(Request $request, Course $course, User $user)
    {
        $currUser = auth()->user();
        $currentTeam = $currUser->currentTeam;
        if (!$currUser->isJSCoach() && !($currUser->isJSVerantwortlich() && $currentTeam->users->contains($user)) && $currUser->id != $user->id) {
            return redirect()->back()->with('error', 'Du hast keine Berechtigung, um diesen Status zu ändern.');
        }

        if (!$course->isInPast()) {
            return redirect()->back()->with('error', 'Du kannst diesen Status nicht ändern, wenn der Kurs noch nicht abgeschlossen ist.');
        }

        try {
            DB::transaction(function () use ($user, $course) {
                if ($user->courses()->where('course_id', $course->id)->exists()) {
                    $user->courses()->updateExistingPivot($course->id, [
                        'status' => 'cancelled',
                        'cancelled_at' => now()
                    ]);
                } else {
                    $user->courses()->attach($course->id, [
                        'status' => 'cancelled',
                        'cancelled_at' => now()
                    ]);
                }
            });
    
            return redirect()->back()->with('success', $currUser->id != $user->id ? $user->name . ' hat an diesem Kurs nicht teilgenommen.' : 'Du hast an diesem Kurs nicht teilgenommen.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten.');
        }
    }

    public function deleteStatus(Request $request, Course $course)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['user_id']);

        try {
            $course->users()->detach($user->id);
            return redirect()->back()->with('success', "{$user->name}' wurde aus diesem Kurs entfernt.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Es gab einen Fehler beim Löschen.');
        }
    }
}
