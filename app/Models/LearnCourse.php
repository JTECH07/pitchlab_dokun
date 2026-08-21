<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearnCourse extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function lessons()
    {
        return $this->hasMany(LearnLesson::class, 'course_id', 'id')->orderBy('sort_order');
    }
}
