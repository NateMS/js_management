<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function teamUsers()
    {
        $currentTeam = auth()->user()->currentTeam;
        $users = $currentTeam->users;

        return view('users.team', compact('users'));
    }
}
