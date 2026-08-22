<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyEvent extends Model
{
    protected $guarded = [];
    protected $casts = ['meta' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
