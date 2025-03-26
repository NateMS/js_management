<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\Jetstream;
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
            'birthdate' => 'date:Y-m-d',
        ];
    }

    public static function getJSCoachMail()
    {
        $admin = User::where('is_js_coach', true)->first();

        if ($admin) {
            return $admin->email;
        }
        return env('MAIL_RECIPIENT');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot(['status', 'signed_up_at', 'registered_at', 'completed_at', 'cancelled_at'])
            ->withTimestamps();
    }

    public function attendedCourses()
    {
        return $this->courses()->wherePivot('status', 'attended');
    }

    public function getCoursesByStatus($status = null)
    {
        $query = $this->courses()->notHidden();

        if ($status) {
            $query->wherePivot('status', $status);
        }

        return $query->get();
    }

    public function courseTypes()
    {
        return $this->belongsToMany(CourseType::class)->withTimestamps();
    }

    public function isJSCoach()
    {
        return $this->is_js_coach;
    }

    public function getformattedBirthdateAttribute()
    {
        return Carbon::parse($this->birthdate)->format('d.m.Y');
    }

    public function getIsoformattedBirthdateAttribute()
    {
        return Carbon::parse($this->birthdate)->format('Y-m-d');
    }

    public function getRoleAttribute()
    {
        if ($this->currentTeam) {
            $userInTeam = $this->currentTeam->users->find($this->id);

            if ($userInTeam) {
                return Jetstream::findRole($this->currentTeam->users->where('id', $this->id)->first()->membership->role)->name;
            }
        }
        return '';
    }

    public function canManageTeamMembers($team)
    {
        return $this->ownsTeam($team) || $this->hasTeamPermission($team, 'manageMembers');
    }

    public function canEditCourse(Course $course) : bool
    {
        if ($this->isJSCoach()) {
            return true;
        }

        if (!$this->isJSVerantwortlich()) {
            return false;
        }

        return $course->courseType->teams->contains('id', $this->currentTeam->id);
    }

    public function canEditUser(User $user) : bool
    {
        if ($this->isJSCoach()) {
            return true;
        }

        if (!$this->isJSVerantwortlich()) {
            return false;
        }

        return $this->currentTeam->users->contains($user);
    }

    public function canAccessCourse(Course $course) : bool
    {
        if ($this->isJSCoach()) {
            return true;
        }

        return $course->courseType->teams->contains('id', $this->currentTeam->id);
    }

    public function getCourseRevalidationDate()
    {   
        $course = $this->courses()
            ->where('status', 'attended')
            ->whereHas('courseType', function ($query) {
                $query->where('requires_repetition', true);
            })
            ->latest('date_start')
            ->first();

        if ($course) {
            $updatedYear = Carbon::parse($course->date_start)->format('Y');

            $validityYear = $updatedYear + 2;
    
            $validUntil = Carbon::createFromDate($validityYear, 12, 31)->format('d.m.Y');
        } else {
            $validUntil = false;
        }
        return $validUntil;
    }

    public function isJSVerantwortlich() {
        return $this->hasRoleInCurrentTeam('js_manager') || $this->isJSCoach();
    }

    public function hasRoleInCurrentTeam($role)
    {
        if ($this->currentTeam) {
            $userInTeam = $this->currentTeam->users->find($this->id);

            if ($userInTeam) {
                return Jetstream::findRole($this->currentTeam->users->where('id', $this->id)->first()->membership->role)->key === $role;
            }
        }
        return false;
    }

    public function scopeExcludeOwners($query, Team $team)
    {
        return $query->where('user_id', '!=', $team->user_id);
    }

    public function scopeWithTeams($query)
    {
        return $query->whereHas('teams');
    }

    public function scopeAged18OrOlder($query)
    {
        $query->where('birthdate', '<=', Carbon::now()->subYears(18)->toDateString());
    }

    public function scopeAgedUnder18($query)
    {
        $query->where('birthdate', '>', Carbon::now()->subYears(18)->toDateString());
    }

    public function getNextCourseDateAttribute()
    {
        $nextCourse = $this->courses()
            ->where('date_end', '>=', Carbon::now())
            ->where('course_user.status', '!=', 'cancelled')
            ->orderBy('date_start')
            ->value('date_start');
        
        return $nextCourse ? $nextCourse->format('d.m.Y') : '-';
    }

    public function hasAttendedKidsCourse()
    {
        return $this->courses()
            ->where('course_user.status', 'attended')
            ->whereHas('courseType', function ($query) {
                $query->where('is_kids_course', true);
            })
            ->exists();
    }

    public function hasAttendedUnder18Course()
    {
        return $this->courses()
            ->where('course_user.status', 'attended')
            ->whereHas('courseType', function ($query) {
                $query->where('maximum_age', '<=', 18);
            })
            ->exists();
    }

    public function getRevalidationColorClass()
    {
        $revalidationDate = Carbon::parse($this->getCourseRevalidationDate());

        if (!$revalidationDate) {
            return '';
        }

        if ($revalidationDate->isPast()) {
            return 'text-red-800';
        }
        
        if ($revalidationDate->isBetween(now(), now()->addMonths(18))) {
            return 'text-yellow-600';
        }
        
        return 'text-green-700';
    }

}
