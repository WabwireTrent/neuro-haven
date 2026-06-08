<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{
    protected $fillable = ['user_id', 'assessment_type', 'score', 'severity', 'responses', 'completed_at'];

    protected $casts = [
        'responses' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
