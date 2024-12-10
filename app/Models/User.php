<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birthdate',
        'js_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot(['status', 'signed_up_at', 'registered_at', 'completed_at', 'cancelled_at'])
            ->withTimestamps();
    }

    public function getCoursesByStatus($status = null)
    {
        $query = $this->courses();

        if ($status) {
            $query->wherePivot('status', $status);
        }

        return $query->get();
    }

        /**
     * Get detailed license renewal status
     */
    public function getLicenseRenewalStatus()
    {
        // Find the last attended course
        $lastAttendedCourse = $this->courses()
            ->wherePivot('status', 'attended')
            ->latest('pivot_attended_at')
            ->first();

        // If no courses attended
        if (!$lastAttendedCourse) {
            return [
                'needs_renewal' => true,
                'last_course_year' => null,
                'next_renewal_year' => Carbon::now()->year,
                'years_since_last_course' => null,
                'pending_registrations' => $this->courses()
                    ->wherePivot('status', 'signed_up')
                    ->count()
            ];
        }

        // Get the year of the last attended course
        $lastCourseYear = Carbon::parse($lastAttendedCourse->pivot->attended_at)->year;
        
        // Calculate next renewal year
        $nextRenewalYear = $lastCourseYear + 2;
        
        // Current year
        $currentYear = Carbon::now()->year;

        return [
            'needs_renewal' => $currentYear >= $nextRenewalYear,
            'last_course_year' => $lastCourseYear,
            'next_renewal_year' => $nextRenewalYear,
            'years_since_last_course' => $currentYear - $lastCourseYear,
            'pending_registrations' => $this->courses()
                ->wherePivot('status', 'signed_up')
                ->count()
        ];
    }

    public function courseTypes()
    {
        return $this->belongsToMany(CourseType::class);
    }

    public function isJSCoach()
    {
        return $this->is_js_coach;
    }

    public function canManageTeamMembers($team)
{
    return $this->ownsTeam($team) || $this->hasTeamPermission($team, 'manageMembers');
}

}
