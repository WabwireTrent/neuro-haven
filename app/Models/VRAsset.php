<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'description', 'category', 'duration_minutes', 'image_path', 'file_path', 'file_type', 'difficulty_level', 'therapeutic_benefits', 'is_active', 'average_rating'])]
class VRAsset extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
        'therapeutic_benefits' => 'json',
    ];

    // Scopes for filtering
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    public function scopeMostPopular($query, $limit = 10)
    {
        return $query->orderBy('usage_count', 'desc')->limit($limit);
    }

    public function scopeHighestRated($query, $limit = 10)
    {
        return $query->orderBy('average_rating', 'desc')->limit($limit);
    }
}
