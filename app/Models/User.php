<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'onboarding_completed', 'preferred_mood', 'therapy_concerns', 'therapy_preference'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTherapist(): bool
    {
        return $this->role === 'therapist';
    }

    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    // Relationships
    public function moods()
    {
        return $this->hasMany(Mood::class);
    }

    public function vrSessions()
    {
        return $this->hasMany(VRSession::class);
    }

    // Therapist relationships
    public function assignedPatients()
    {
        return $this->belongsToMany(
            User::class,
            'therapist_patient_assignments',
            'therapist_id',
            'patient_id'
        )->withPivot('status', 'notes', 'assigned_at')
         ->wherePivot('status', 'active');
    }

    public function assignedTherapists()
    {
        return $this->belongsToMany(
            User::class,
            'therapist_patient_assignments',
            'patient_id',
            'therapist_id'
        )->withPivot('status', 'notes', 'assigned_at')
         ->wherePivot('status', 'active');
    }

    public function therapistAssignments()
    {
        return $this->hasMany(TherapistPatientAssignment::class, 'therapist_id');
    }

    public function patientAssignments()
    {
        return $this->hasMany(TherapistPatientAssignment::class, 'patient_id');
    }

    // Notifications
    public function notifications()
    {
        return $this->hasMany(SystemNotification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function getCurrentStreak()
    {
        $streak = 0;
        $currentDate = today()->toDateString();

        while (true) {
            $moodExists = $this->moods()
                              ->whereDate('mood_date', $currentDate)
                              ->exists();

            if (!$moodExists) {
                break;
            }

            $streak++;
            $currentDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));
        }

        return $streak;
    }
}
