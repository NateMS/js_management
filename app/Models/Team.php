<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function courseTypes()
    {
        return $this->belongsToMany(CourseType::class, 'course_type_team');
    }

}
