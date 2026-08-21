<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearnWord extends Model
{
    protected $guarded = [];

    public function lesson()
    {
        return $this->belongsTo(LearnLesson::class);
    }
}
