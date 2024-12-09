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
            return "1 Tag";
        } else {
            return "$diffInDays Tage";
        }
    }

    public function getParticipationNumberAttribute()
    {
        return $this->users()->count();
    }

    public function scopeRegistrationDeadlineNotPassed($query)
    {
        return $query->where('registration_deadline', '>=', now());
    }

    public function scopeAvailableToTeam($query, $teamId)
    {
        return $query->whereHas('courseType', function ($courseTypeQuery) use ($teamId) {
            $courseTypeQuery->whereHas('teams', function ($teamQuery) use ($teamId) {
                $teamQuery->where('id', $teamId);
            });
        });
    }

    public function scopeAvailableToCurrentTeam($query)
    {
        $user = Auth::user();

        if (!$user || !$user->team) {
            return $query;
        }

        $teamId = $user->team->id;

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
                    $subQ->whereRaw("(strftime('%Y', courses.date_start) - ? >= course_types.minimum_age)", [$birthYear])
                        ->orWhereNull('course_types.minimum_age');
                });
                
                $q->where(function ($subQ) use ($birthYear) {
                    $subQ->whereRaw("(strftime('%Y', courses.date_start) - ? <= course_types.maximum_age)", [$birthYear])
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

        return $query->whereHas('courseType', function ($courseTypeQuery) use ($user) {
            $courseTypeQuery->where(function ($query) use ($user) {
                $query->whereNull('prerequisite_course_type_id')
                    ->orWhereHas('prerequisiteCourseType.users', function ($userQuery) use ($user) {
                        $userQuery->where('user_id', $user->id);
                    });
            });
        });
}



    /**
     * Check if registration is still open
     */
    public function isRegistrationOpen()
    {
        return Carbon::now()->lessThanOrEqualTo($this->registration_deadline);
    }

    public function scopeWithUserStatus($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();

        $query->with(['userStatus' => function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->select('course_id', 'status', 'user_id');
        }]);
    }

    public function userStatus()
    {
        return $this->hasOne(CourseUser::class, 'course_id')
            ->where('user_id', auth()->id());
    }

}
