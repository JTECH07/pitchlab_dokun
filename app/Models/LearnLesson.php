<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearnLesson extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function course()
    {
        return $this->belongsTo(LearnCourse::class, 'course_id', 'id');
    }

    public function words()
    {
        return $this->hasMany(LearnWord::class, 'lesson_id', 'id')->orderBy('sort_order');
    }

    public function progress()
    {
        return $this->hasMany(LearnProgress::class, 'lesson_id', 'id');
    }
}
