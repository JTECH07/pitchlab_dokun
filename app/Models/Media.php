<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }

    public function getUrlAttribute()
    {
        if ($this->path && file_exists(public_path($this->path))) {
            return asset($this->path);
        }
        if ($this->path && str_starts_with($this->path, 'http')) {
            return $this->path;
        }
        return asset('images/hero/hero_dokun.png');
    }
}
