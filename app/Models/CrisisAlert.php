<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrisisAlert extends Model
{
    protected $fillable = [
        'user_id', 'triggered_by', 'severity', 'message', 'details',
        'is_resolved', 'resolved_at', 'resolved_by',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
