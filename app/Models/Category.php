<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];
    public function savoirFaires() { return $this->hasMany(SavoirFaire::class); }

    public function getEmojiAttribute()
    {
        return match ($this->slug) {
            'poterie-terre-cuite', 'artisanat-creation' => '🏺',
            'art-masques-sacres' => '🎭',
            'textile-tissage', 'tissage-kanvo-indigo' => '🧵',
            'vannerie-fibres', 'vannerie-raphia' => '🧺',
            default => '🎨',
        };
    }
}
