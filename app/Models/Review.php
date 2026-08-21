<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'artisan_id', 'reservation_request_id',
        'rating', 'comment', 'status', 'moderated_by', 'moderated_at',
    ];

    protected $casts = ['moderated_at' => 'datetime'];

    public function user()    { return $this->belongsTo(User::class); }
    public function artisan() { return $this->belongsTo(Artisan::class); }
    public function reservation() { return $this->belongsTo(ReservationRequest::class, 'reservation_request_id'); }
    public function moderator()   { return $this->belongsTo(User::class, 'moderated_by'); }
}
