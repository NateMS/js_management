<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Carbon\Carbon;

class CourseUser extends Pivot
{
    protected $table = 'course_user';

    /**
     * Get the formatted status.
     *
     * @return string
     */
    public function getFormattedStatusAttribute()
    {
        return match ($this->status) {
            'signed_up' => 'Eingetragen',
            'registered' => 'Angemeldet',
            'attended' => 'Teilgenommen',
            'cancelled' => 'Abgesagt',
            'waiting_list' => 'Warteliste',
            default => 'Unbekannt',
        };
    }

    public static function getTimestampField(string $status)
    {
        return match ($status) {
            'signed_up' => 'signed_up_at',
            'registered' => 'registered_at',
            'attended' => 'completed_at',
            'cancelled' => 'cancelled_at',
            'waiting_list' => 'waiting_list_at',
            default => 'Unbekannt',
        };
    }

    public function getFormattedTimestampAttribute()
    {
        return match ($this->status) {
            'signed_up' => Carbon::parse($this->signed_up_at)->format('d.m.Y'),
            'registered' => Carbon::parse($this->registered_at)->format('d.m.Y'),
            'attended' => Carbon::parse($this->completed_at)->format('d.m.Y'),
            'cancelled' => Carbon::parse($this->cancelled_at)->format('d.m.Y'),
            'waiting_list' => Carbon::parse($this->waiting_list_at)->format('d.m.Y'),
            default => '/',
        };
    }

    /**
     * Define the relationship to the course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Define the relationship to the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
