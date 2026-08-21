<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtisanFavorite extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }
}
