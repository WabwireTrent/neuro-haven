<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    protected $fillable = [
        'user_id',
        'mood',
        'mood_scale',
        'note',
        'mood_date',
    ];

    protected $casts = [
        'mood_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereDate('mood_date', '>=', now()->startOfWeek())
                     ->whereDate('mood_date', '<=', now()->endOfWeek());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereDate('mood_date', '>=', now()->startOfMonth())
                     ->whereDate('mood_date', '<=', now()->endOfMonth());
    }
}
