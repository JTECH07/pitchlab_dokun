<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('earned_at');
    }
}
