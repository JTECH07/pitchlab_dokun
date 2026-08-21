<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearnProgress extends Model
{
    protected $guarded = [];

    protected $casts = ['completed_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(LearnLesson::class);
    }
}
