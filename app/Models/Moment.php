<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Moment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'reservation_request_id', 'artisan_id', 'title', 'description',
        'video_path', 'cover_path', 'status', 'share_token', 'moderated_by', 'moderated_at',
    ];

    protected $casts = ['moderated_at' => 'datetime'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->share_token)) {
                $model->share_token = \Illuminate\Support\Str::random(40);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservation()
    {
        return $this->belongsTo(ReservationRequest::class, 'reservation_request_id');
    }

    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getVideoUrlAttribute()
    {
        if ($this->video_path) {
            return Storage::disk('public')->url($this->video_path);
        }
        return null;
    }

    public function getCoverUrlAttribute()
    {
        if ($this->cover_path && Storage::disk('public')->exists($this->cover_path)) {
            return Storage::disk('public')->url($this->cover_path);
        }
        return asset('images/hero/hero_dokun.png');
    }
}
