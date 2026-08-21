<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quartier extends Model
{
    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
