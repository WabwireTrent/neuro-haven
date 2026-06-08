<?php

namespace App\Services;

use App\Models\User;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function notify(int $userId, string $type, string $title, string $message, ?array $data = null, string $severity = 'info'): SystemNotification
    {
        $notification = SystemNotification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'severity' => $severity
        ]);

        // Broadcast notification via WebSocket if available
        $this->broadcastNotification($notification);

        return $notification;
    }

    /**
     * Notify a therapist about a patient reaching critical mood
     */
    public function notifyCriticalMood(User $therapist, User $patient, int $moodLevel): void
    {
        $this->notify(
            $therapist->id,
            type: 'crisis_alert',
            title: "🚨 Crisis Alert: {$patient->name}",
            message: "{$patient->name} has logged a critical mood level ({$moodLevel}/10)",
            data: [
                'patient_id' => $patient->id,
                'patient_name' => $patient->name,
                'mood_level' => $moodLevel
            ],
            severity: 'critical'
        );

        Log::warning("Crisis alert for {$patient->name}", ['mood_level' => $moodLevel]);
    }

    /**
     * Notify about streak milestones
     */
    public function notifyMilestone(User $user, int $streakDays): void
    {
        $message = match (true) {
            $streakDays == 7 => "🎉 7-Day Milestone! You're on fire!",
            $streakDays == 30 => "🏆 30-Day Champion! Incredible progress!",
            $streakDays == 100 => "👑 100-Day Legend! You're unstoppable!",
            $streakDays % 10 == 0 => "{$streakDays}-Day Streak! Keep it up!",
            default => null
        };

        if ($message) {
            $this->notify(
                $user->id,
                type: 'milestone',
                title: 'Milestone Achievement!',
                message: $message,
                data: ['streak_days' => $streakDays],
                severity: 'info'
            );
        }
    }

    /**
     * Notify about streak warning
     */
    public function notifyStreakWarning(User $user, int $currentStreak): void
    {
        if ($currentStreak > 0) {
            $this->notify(
                $user->id,
                type: 'streak_warning',
                title: '⏰ Streak Warning',
                message: "Don't lose your {$currentStreak}-day streak! Log your mood or do a session today.",
                data: ['streak_days' => $currentStreak],
                severity: 'warning'
            );
        }
    }

    /**
     * Notify therapist about patient assignment
     */
    public function notifyPatientAssignment(User $therapist, User $patient, string $action = 'assigned'): void
    {
        $messageText = match ($action) {
            'removed' => "{$patient->name} has been removed from your patient list.",
            default => "{$patient->name} has been assigned to you as a patient."
        };

        $this->notify(
            $therapist->id,
            type: 'assignment',
            title: ucfirst($action) . ' Patient',
            message: $messageText,
            data: [
                'patient_id' => $patient->id,
                'patient_name' => $patient->name,
                'action' => $action
            ],
            severity: 'info'
        );
    }

    /**
     * Notify patient about therapist assignment
     */
    public function notifyTherapistAssignment(User $patient, User $therapist, string $action = 'assigned'): void
    {
        $messageText = match ($action) {
            'removed' => "{$therapist->name} is no longer your therapist.",
            default => "You have been assigned to {$therapist->name} as your therapist."
        };

        $this->notify(
            $patient->id,
            type: 'assignment',
            title: ucfirst($action) . ' Therapist',
            message: $messageText,
            data: [
                'therapist_id' => $therapist->id,
                'therapist_name' => $therapist->name,
                'action' => $action
            ],
            severity: 'info'
        );
    }

    /**
     * Notify therapist about a completed VR session report
     */
    public function notifyVRSessionReport(User $therapist, User $patient, $session): void
    {
        $duration = $session->session_duration ?? 0;
        $mins = floor($duration / 60);
        $secs = $duration % 60;
        $durationStr = $mins > 0 ? "{$mins}m {$secs}s" : "{$secs}s";
        $moodChange = $session->mood_before && $session->mood_after
            ? ($session->mood_after - $session->mood_before)
            : null;
        $changeStr = $moodChange !== null
            ? ($moodChange >= 0 ? "+{$moodChange}" : $moodChange)
            : 'N/A';

        $this->notify(
            $therapist->id,
            type: 'vr_session_report',
            title: "📋 VR Session Report: {$patient->name}",
            message: "{$patient->name} completed \"{$session->vr_asset_title}\" ({$durationStr}, mood: {$changeStr})",
            data: [
                'session_id' => $session->id,
                'patient_id' => $patient->id,
                'patient_name' => $patient->name,
                'vr_asset_title' => $session->vr_asset_title,
                'duration_seconds' => $session->session_duration,
                'mood_before' => $session->mood_before,
                'mood_after' => $session->mood_after,
                'session_quality' => $session->session_quality,
            ],
            severity: 'info'
        );
    }

    /**
     * Get the primary therapist for a patient
     */
    public function getPatientTherapist(User $patient): ?User
    {
        return $patient->assignedTherapists()->where('status', 'active')->first();
    }

    /**
     * Broadcast notification via Node.js WebSocket server
     */
    private function broadcastNotification(SystemNotification $notification): void
    {
        try {
            // Send to Node.js backend via HTTP for WebSocket broadcasting
            $backendUrl = config('services.backend.url', 'http://localhost:3000');
            $client = new \GuzzleHttp\Client();
            $client->post("{$backendUrl}/api/broadcast-notification", [
                'json' => [
                    'user_id' => $notification->user_id,
                    'notification_id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'severity' => $notification->severity,
                    'data' => $notification->data
                ],
                'timeout' => 2,
                'connect_timeout' => 2
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast notification: ' . $e->getMessage());
        }
    }

    /**
     * Get unread notifications count for a user
     */
    public function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications()->update(['read_at' => now()]);
    }
}
