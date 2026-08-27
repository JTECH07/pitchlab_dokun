<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ActorRequest extends Model
{
    use Notifiable;
    protected $fillable = [
        'role', 'name', 'email', 'phone', 'organization',
        'motivation', 'extra_data', 'status', 'admin_notes', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'extra_data' => 'array',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
