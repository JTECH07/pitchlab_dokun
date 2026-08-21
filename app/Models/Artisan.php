<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artisan extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'pending_profile_data' => 'array',
    ];

    public function savoirFaires()
    {
        return $this->belongsToMany(SavoirFaire::class, 'artisan_savoir_faire');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function reservations()
    {
        return $this->hasMany(ReservationRequest::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }
        $firstMedia = $this->media()->where('type', 'image')->first();
        if ($firstMedia && $firstMedia->path) {
            return asset($firstMedia->path);
        }
        return asset('images/hero/hero_dokun.png');
    }
}
