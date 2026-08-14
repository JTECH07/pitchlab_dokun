<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'artisan_id', 'experience_id', 'user_id', 'visitor_name', 'visitor_phone', 'visitor_email',
        'requested_date', 'guests_count', 'experience_type', 'message', 'status', 'total_amount',
        'currency', 'payment_method', 'payment_status', 'reference'
    ];

    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }

    public function experience() { return $this->belongsTo(Experience::class); }
    public function user() { return $this->belongsTo(User::class); }
}
