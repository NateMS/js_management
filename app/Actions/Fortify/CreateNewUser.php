<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\TeamInvitation;
use Laravel\Jetstream\Contracts\AddsTeamMembers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date'],
            'js_number' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'birthdate' => $input['birthdate'],
                'js_number' => $input['js_number'] ?? null,
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) {

                // $invitation = TeamInvitation::where('email', $user->email)->first();
                // if ($invitation == null) {
                //     $this->createTeam($user);
                // } else {
                //     $this->assignTeam($user, $invitation);
                // }                
            });
        });
    }

    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => false,
        ]));

        $user->switchTeam($team = $user->ownedTeams()->first());
    }

    protected function assignTeam(User $user, TeamInvitation $invitation)
    {
        app(AddsTeamMembers::class)->add(
            $invitation->team->owner,
            $invitation->team,
            $invitation->email,
            $invitation->role
        );
    
        $user->current_team_id = $invitation->team_id;
        $user->save();
    }
}
