<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood' => 'required|in:excellent,happy,calm,anxious,sad,stressed',
            'mood_scale' => 'required|integer|min:1|max:10',
            'note' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        
        Mood::create([
            'user_id' => $user->id,
            'mood' => $validated['mood'],
            'mood_scale' => $validated['mood_scale'],
            'note' => $validated['note'] ?? null,
            'mood_date' => today(),
        ]);

        // Check for critical mood and notify assigned therapists
        if ($validated['mood_scale'] <= 3) {
            $therapists = $user->assignedTherapists;
            foreach ($therapists as $therapist) {
                $this->notificationService->notifyCriticalMood($therapist, $user, $validated['mood_scale']);
            }
        }

        // Check for streak milestone
        $currentStreak = $user->getCurrentStreak();
        $this->notificationService->notifyMilestone($user, $currentStreak);

        return redirect()->route('mood.tracker')
                        ->with('success', 'Mood logged successfully!');
    }

    public function getWeeklyData()
    {
        $moods = Mood::forUser(auth()->id())
                     ->thisWeek()
                     ->orderBy('mood_date')
                     ->get();

        return response()->json($moods);
    }
}

