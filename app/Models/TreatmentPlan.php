<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model
{
    protected $fillable = [
        'patient_id', 'therapist_id', 'title', 'description', 'goals',
        'status', 'started_at', 'target_end_date', 'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'target_end_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    public function milestones()
    {
        return $this->hasMany(PlanMilestone::class)->orderBy('position');
    }

    public function completedMilestones()
    {
        return $this->milestones()->whereNotNull('completed_at');
    }
}
