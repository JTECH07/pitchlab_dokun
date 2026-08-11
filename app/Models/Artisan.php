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
}
