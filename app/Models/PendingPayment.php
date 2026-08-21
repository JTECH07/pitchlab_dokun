<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingPayment extends Model
{
    protected $fillable = ['reference', 'reservation_data', 'fedapay_transaction_id', 'status'];
    protected $casts    = ['reservation_data' => 'array'];
}
