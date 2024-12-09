<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function teamUsers()
    {
        // Ensure the logged-in user is a manager
        if (!auth()->user()->is_manager) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch users belonging to the manager's team
        $teamId = auth()->user()->team_id;
        $users = User::where('team_id', $teamId)->get();

        // Return the view with the list of users
        return view('users.team', compact('users'));
    }
}
