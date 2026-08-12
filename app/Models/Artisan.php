<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artisan extends Model
{
    protected $guarded = [];

    public function savoirFaires()
    {
        return $this->belongsToMany(SavoirFaire::class, 'artisan_savoir_faire');
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function getImageUrlAttribute()
    {
        $firstMedia = $this->media()->where('type', 'image')->first();
        if ($firstMedia && $firstMedia->path) {
            return asset($firstMedia->path);
        }
        return asset('images/hero/hero_dokun.png');
    }
}
