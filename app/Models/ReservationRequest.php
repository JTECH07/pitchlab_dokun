<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationRequest extends Model
{
    protected $fillable = [
        'artisan_id', 'visitor_name', 'visitor_phone', 'visitor_email',
        'requested_date', 'guests_count', 'experience_type', 'message', 'status'
    ];

    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }
}
