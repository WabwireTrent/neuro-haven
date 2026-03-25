<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VRSession extends Model
{
    protected $table = 'vr_sessions';

    protected $fillable = [
        'user_id',
        'vr_asset_id',
        'vr_asset_title',
        'session_duration',
        'started_at',
        'completed_at',
        'mood_before',
        'mood_after',
        'device_type',
        'session_quality',
        'notes'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'session_duration' => 'integer',
        'mood_before' => 'integer',
        'mood_after' => 'integer',
        'session_quality' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for analytics
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('started_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('started_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAsset($query, $assetId)
    {
        return $query->where('vr_asset_id', $assetId);
    }

    // Helper methods
    public function getMoodImprovementAttribute()
    {
        if ($this->mood_after && $this->mood_before) {
            return $this->mood_after - $this->mood_before;
        }
        return null;
    }

    public function getIsCompletedAttribute()
    {
        return !is_null($this->completed_at);
    }
}
