<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltySummary extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_activity_date' => 'date',
        'streak_updated_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
