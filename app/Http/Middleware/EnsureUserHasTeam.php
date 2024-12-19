<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user->allTeams()->count() > 0) {
            if ($user->current_team_id == null) {
                $user->current_team_id = $user->allTeams()->first()->id;
                $user->save();
            }
            return $next($request);
        }
        
        return redirect()->route('home');
    }
}
