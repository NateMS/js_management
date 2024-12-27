<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{

    protected $fillable = [
        'course_nr',
        'name',
        'course_type_id',
        'location',
        'date_start',
        'date_end',
        'prerequisites',
        'registration_deadline',
        'notes',
        'link',
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
        'registration_deadline' => 'datetime',
    ];

    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(CourseUser::class)
            ->withPivot('status', 'signed_up_at', 'registered_at', 'completed_at', 'cancelled_at')
            ->withTimestamps();
    }


    public function getFormattedDateRangeAttribute()
    {
        if ($this->date_start->equalTo($this->date_end)) {
            return $this->date_start->format('d.m.Y');
        }

        return $this->date_start->format('d.m.Y') . ' - ' . $this->date_end->format('d.m.Y');
    }

    public function getDurationAttribute()
    {
        $diffInDays = $this->date_start->diffInDays($this->date_end) + 1;

        if ($diffInDays == 1) {
            return "";
        } else {
            return " ($diffInDays Tage)";
        }
    }

    public function getParticipationNumberAttribute()
    {
        return $this->users()->count();
    }

    public function isInPast() : bool
    {
        return $this->date_start <= now();
    }

    public function scopeRegistrationDeadlineNotPassed($query)
    {
        return $query->where('registration_deadline', '>=', now());
    }

    public function scopeAvailableToTeam($query, $teamId)
    {
        return $query->whereHas('courseType', function ($courseTypeQuery) use ($teamId) {
            $courseTypeQuery->whereHas('teams', function ($teamQuery) use ($teamId) {
                $teamQuery->where('team_id', $teamId);
            });
        });
    }

    public function scopeAvailableToCurrentTeam($query)
    {
        $user = Auth::user();

        if (!$user || !$user->currentTeam) {
            return $query;
        }

        $teamId = $user->currentTeam->id;

        return $query->availableToTeam($teamId);
    }

    public function scopePassesAgeRequirement($query)
    {
        $user = Auth::user();
        if (!$user || !$user->birthdate) {
            return $query;
        }

        $birthdate = Carbon::parse($user->birthdate);
        $birthYear = $birthdate->year;

        return $query->whereHas('courseType', function ($courseTypeQuery) use ($birthYear) {
            $courseTypeQuery->where(function ($q) use ($birthYear) {
                $q->where(function ($subQ) use ($birthYear) {
                    $subQ->whereRaw("(YEAR(courses.date_start) - ? >= course_types.minimum_age)", [$birthYear])
                        ->orWhereNull('course_types.minimum_age');
                });
                
                $q->where(function ($subQ) use ($birthYear) {
                    $subQ->whereRaw("(YEAR(courses.date_start) - ? <= course_types.maximum_age)", [$birthYear])
                        ->orWhereNull('course_types.maximum_age');
                });
            });
        });
    }

    public function scopeFullfillsCourseTypePrerequisite($query)
    {
        $user = Auth::user();

        if (!$user) {
            return $query;
        }

        $attendedCourseTypes = $user->attendedCourses()->pluck('course_type_id')->unique()->toArray();

        return $query->whereHas('courseType', function ($courseTypeQuery) use ($attendedCourseTypes) {
            $courseTypeQuery->where(function ($query) use ($attendedCourseTypes) {
                $query->whereNull('prerequisite_course_type_id')
                      ->orWhereIn('prerequisite_course_type_id', $attendedCourseTypes);
            })->where(function ($query) use ($attendedCourseTypes) {
                $query->where('can_only_attend_once', false)
                      ->orWhereNotIn('id', $attendedCourseTypes);
            });
        });
    }

    public function scopeNotHidden($query)
    {
        return $query->where('is_hidden', false);
    }

    public function isRegistrationOpen()
    {
        return Carbon::now()->lessThanOrEqualTo($this->registration_deadline);
    }

    public function scopeWithoutUser($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $query->whereDoesntHave('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    public function scopePastCourses($query)
    {
        return $query->where('date_start', '<=', now());
    }

    public function scopeFutureCourses($query)
    {
        return $query->where('date_start', '>', now());
    }

    public function userStatus($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->hasOne(CourseUser::class, 'course_id')
            ->where('user_id', $userId)->first();
    }

    public function availableUsers()
    {
        if (auth()->user()->isJSCoach()) {
            $teamUsers = User::withTeams()->get();
        } else {
            $teamUsers = auth()->user()->currentTeam->users;
        }
        $signedUpUserIds = $this->users()->pluck('users.id')->toArray();

        return $teamUsers->filter(function ($user) use ($signedUpUserIds) {
            if (in_array($user->id, $signedUpUserIds)) {
                return false;
            }

            if ($user->id == auth()->user()->currentTeam->user_id) {
                return false;
            }

            if (!auth()->user()->isJSCoach() && $user->isJSCoach()) {
                return false;
            }

            return $this->meetsAgeRequirementForUser($user) &&
                $this->meetsPrerequisiteRequirementForUser($user);
        });
    }

    protected function meetsAgeRequirementForUser($user)
    {
        if (!$user->birthdate) {
            return false; // User must have a birthdate
        }

        $birthYear = Carbon::parse($user->birthdate)->year;
        $courseYear = Carbon::parse($this->date_start)->year;

        $minAge = $this->courseType->minimum_age;
        $maxAge = $this->courseType->maximum_age;

        return ($minAge === null || ($courseYear - $birthYear >= $minAge)) &&
            ($maxAge === null || ($courseYear - $birthYear <= $maxAge));
    }

    protected function meetsPrerequisiteRequirementForUser($user)
    {
        $attendedCourseTypes = $user->attendedCourses()
            ->with('courseType')
            ->get()
            ->pluck('courseType.id')
            ->unique()
            ->toArray();

        $prerequisiteId = $this->courseType->prerequisite_course_type_id;

        if ($this->courseType->can_only_attend_once && in_array($this->courseType->id, $attendedCourseTypes)) {
            return false;
        }

        return $prerequisiteId === null || in_array($prerequisiteId, $attendedCourseTypes);
    }

}
