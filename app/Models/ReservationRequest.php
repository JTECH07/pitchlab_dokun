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
        'service_fee', 'currency', 'payment_method', 'payment_status', 'reference',
        'fedapay_transaction_id', 'kkiapay_transaction_id', 'qr_code_token',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->qr_code_token)) {
                $model->qr_code_token = \Illuminate\Support\Str::random(40);
            }
        });
    }

    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }

    public function experience() { return $this->belongsTo(Experience::class); }
    public function user() { return $this->belongsTo(User::class); }
}
