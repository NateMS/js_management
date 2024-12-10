<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCourseAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isJSCoach()) {
            return $next($request);
        }

        $course = $request->route('course');
        if (!$course) {
            return $next($request);
        }

        $currentTeam = $user->currentTeam;
        $courseTypeInTeam = $course->courseType->teams->contains($currentTeam);
        if (!$courseTypeInTeam) {
            return redirect()->route('courses.index')->with('error', 'Du hast keinen Zugriff auf diesen Kurs.');
        }

        return $next($request);
    }
}
