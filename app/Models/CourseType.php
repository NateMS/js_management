<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
    protected $fillable = [
        'name',
        'order',
        'minimum_age',
        'maximum_age',
        'prerequisite_course_type_id',
        'can_only_attend_once',
        'is_kids_course',
        'requires_repetition',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'course_type_team');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function prerequisiteCourseType()
    {
        return $this->belongsTo(CourseType::class, 'prerequisite_course_type_id');
    }

}
