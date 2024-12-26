<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use DB;

class NotificationController extends Controller
{
    public function notifyCourseParticipants(Request $request)
    {
        if ($request->query('key') !== env('NOTIFY_KEY')) {
            abort(403, 'Unauthorized');
        }

        $today = now()->toDateString();

        $courses = Course::whereDate('date_end', $today)->get();

        $counter = 0;
        foreach ($courses as $course) {
            $users = $course->users()->where('status', 'registered')->get();

            foreach ($users as $user) {

                $token = Str::random(32);
                DB::table('course_user')
                ->where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->update(['confirmation_token' => $token]);
                try {
                    Mail::to($user->email)->send(new \App\Mail\CourseEndNotification($course, $token));
                    $counter++;
                } catch (\Exception $e) {
                    \Log::error('Failed to send email to ' . $user->email . ': ' . $e->getMessage());
                }
            }
        }

        return response()->json(['message' => $counter . ' Notifications sent'], 200);
    }

    public function confirm($token)
    {
        $record = DB::table('course_user')->where('confirmation_token', $token)->first();

        if (!$record) {
            return view('courses.confirm', ['message' => 'Die Bestätigung konnte nicht erfolgreich gespeichert werden. Bitte überprüfe den Link oder melde dich an, um die Kursteilnahme zu bestätigen.']);
        }

        DB::table('course_user')
            ->where('id', $record->id)
            ->update(['status' => 'attended', 'completed_at' => now(), 'confirmation_token' => null]);

        return view('courses.confirm', ['message' => 'Du hast deine Teilnahme an diesem Kurs bestätigt. Besten Dank.']);

    }

    public function cancel($token)
    {

        $record = DB::table('course_user')->where('confirmation_token', $token)->first();

        if (!$record) {
            return view('courses.confirm', ['message' => 'Die Bestätigung konnte nicht erfolgreich gespeichert werden. Bitte überprüfe den Link oder melde dich an, um die Kursteilnahme zu bestätigen.']);
        }

        DB::table('course_user')
            ->where('id', $record->id)
            ->update(['status' => 'cancelled', 'cancelled_at' => now(), 'confirmation_token' => null]);

        return  view('courses.confirm', ['message' => 'Danke für deine Bestätigung. Du hast an diesem Kurs nicht teilgenommen.']);
    }
}
