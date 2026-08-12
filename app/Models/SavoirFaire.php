<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavoirFaire extends Model
{
    protected $guarded = [];
    public function category() { return $this->belongsTo(Category::class); }
    public function artisans() { return $this->belongsToMany(Artisan::class, 'artisan_savoir_faire'); }

    public function getImageUrlAttribute()
    {
        $artisan = $this->artisans()->first();
        if ($artisan) {
            return $artisan->image_url;
        }
        return asset('images/hero/hero_dokun.png');
    }
}
