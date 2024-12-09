<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

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
            default => 'Unbekannt',
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
