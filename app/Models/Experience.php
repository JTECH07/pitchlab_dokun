<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Experience extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'price' => 'decimal:0'];
    }

    public function artisan() { return $this->belongsTo(Artisan::class); }
    public function reservations() { return $this->hasMany(ReservationRequest::class); }
}
