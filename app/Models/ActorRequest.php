<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActorRequest extends Model
{
    protected $fillable = [
        'role', 'name', 'email', 'phone', 'organization',
        'motivation', 'status', 'admin_notes', 'reviewed_by', 'reviewed_at',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
