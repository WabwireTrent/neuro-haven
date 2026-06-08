<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use App\Models\User;
use App\Models\VRAsset;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    protected NotificationService $notificationService;

    private array $moodRecommendations = [
        'excellent' => ['category' => 'Inspiration', 'reason' => 'Keep riding that positive energy with an uplifting experience.'],
        'happy' => ['category' => 'Nature', 'reason' => 'Celebrate your good vibes with a beautiful escape into nature.'],
        'calm' => ['category' => 'Meditation', 'reason' => 'Deepen your sense of peace with a guided meditation session.'],
        'anxious' => ['category' => 'Breathing', 'reason' => 'Ease your anxiety with a guided breathing exercise in a serene environment.'],
        'sad' => ['category' => 'Inspiration', 'reason' => 'Lift your spirits with an inspiring mountain view experience.'],
        'stressed' => ['category' => 'Relaxation', 'reason' => 'Release your stress with a calming virtual relaxation session.'],
    ];

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

        // Get session suggestion based on mood
        $recommendation = $this->moodRecommendations[$validated['mood']] ?? null;
        $suggestedSession = null;
        if ($recommendation) {
            $suggestedSession = VRAsset::active()
                ->byCategory($recommendation['category'])
                ->inRandomOrder()
                ->first();

            if (!$suggestedSession) {
                $suggestedSession = VRAsset::active()->inRandomOrder()->first();
            }
        }

        return redirect()->route('mood.tracker')
            ->with('success', 'Mood logged successfully!')
            ->with('suggested_session', $suggestedSession)
            ->with('suggestion_reason', $recommendation['reason'] ?? 'Try a session that matches your current mood.');
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

