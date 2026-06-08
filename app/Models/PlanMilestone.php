<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanMilestone extends Model
{
    protected $fillable = ['treatment_plan_id', 'title', 'description', 'due_date', 'completed_at', 'position'];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(TreatmentPlan::class, 'treatment_plan_id');
    }
}
