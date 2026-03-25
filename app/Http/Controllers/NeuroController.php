<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use App\Models\VRSession;
use Illuminate\Http\Request;

class NeuroController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();
        
        // Get this week's moods
        $weekMoods = Mood::forUser($userId)->thisWeek()->get();
        
        // Get today's mood
        $todayMood = Mood::forUser($userId)->whereDate('mood_date', today())->latest()->first();
        
        // Calculate stats
        $moodCount = $weekMoods->count();
        $weekPercent = $moodCount > 0 ? (($moodCount / 7) * 100) : 0;
        $avgScore = $weekMoods->avg('mood_scale') ?? 0;
        
        // Get streak (consecutive days with mood logged)
        $streak = $this->calculateStreak($userId);

        $user = [
            'name' => auth()->user()->name,
            'streak' => $streak,
            'mood' => $todayMood ? ucfirst($todayMood->mood) : 'Not logged',
            'mood_score' => round($avgScore),
            'week_percent' => round($weekPercent),
            'week_moods' => $weekMoods,
            'today_mood' => $todayMood,
        ];

        return view('dashboard', compact('user'));
    }

    private function calculateStreak($userId)
    {
        $streak = 0;
        $currentDate = today()->toDateString();
        
        while (true) {
            $moodExists = Mood::forUser($userId)
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

    public function moodTracker()
    {
        $userId = auth()->id();
        $todayMood = Mood::forUser($userId)->whereDate('mood_date', today())->latest()->first();
        $recentMoods = Mood::forUser($userId)->orderBy('mood_date', 'desc')->limit(30)->get();

        return view('mood-tracker', compact('todayMood', 'recentMoods'));
    }

    public function therapySessions()
    {
        return view('therapy-sessions');
    }

    public function progressTracking()
    {
        $userId = auth()->id();
        $weekMoods = Mood::forUser($userId)->thisWeek()->get();
        $monthMoods = Mood::forUser($userId)->thisMonth()->get();

        return view('progress-tracking', compact('weekMoods', 'monthMoods'));
    }

    public function vrAssets()
    {
        // Sample VR therapeutic assets
        $assets = [
            [
                'id' => 1,
                'title' => 'Peaceful Forest Walk',
                'description' => 'A calming virtual forest experience to reduce stress and anxiety.',
                'duration' => '10 minutes',
                'category' => 'Relaxation',
                'image' => '/assets/images/vr-forest.jpg',
            ],
            [
                'id' => 2,
                'title' => 'Ocean Meditation',
                'description' => 'Meditate by the virtual ocean waves for mindfulness and tranquility.',
                'duration' => '15 minutes',
                'category' => 'Meditation',
                'image' => '/assets/images/vr-ocean.jpg',
            ],
            [
                'id' => 3,
                'title' => 'Mountain View Therapy',
                'description' => 'Experience breathtaking mountain vistas to promote positive thinking.',
                'duration' => '12 minutes',
                'category' => 'Inspiration',
                'image' => '/assets/images/vr-mountain.jpg',
            ],
            [
                'id' => 4,
                'title' => 'Guided Breathing Exercise',
                'description' => 'Interactive breathing exercises in a serene virtual environment.',
                'duration' => '8 minutes',
                'category' => 'Breathing',
                'image' => '/assets/images/vr-breathing.jpg',
            ],
            [
                'id' => 5,
                'title' => 'Starry Night Relaxation',
                'description' => 'Gaze at a beautiful night sky filled with stars for deep relaxation.',
                'duration' => '20 minutes',
                'category' => 'Relaxation',
                'image' => '/assets/images/vr-stars.jpg',
            ],
            [
                'id' => 6,
                'title' => 'Zen Garden Meditation',
                'description' => 'Find peace in a traditional Japanese zen garden setting.',
                'duration' => '18 minutes',
                'category' => 'Meditation',
                'image' => '/assets/images/vr-zen.jpg',
            ],
        ];

        return view('vr-assets', compact('assets'));
    }

    public function vrAnalytics()
    {
        $userId = auth()->id();

        // VR Session Analytics
        $totalSessions = VRSession::forUser($userId)->count();
        $completedSessions = VRSession::forUser($userId)->completed()->count();
        $totalDuration = VRSession::forUser($userId)->sum('session_duration') ?? 0;
        $avgSessionQuality = VRSession::forUser($userId)->whereNotNull('session_quality')->avg('session_quality') ?? 0;

        // Weekly VR sessions
        $weekVRSessions = VRSession::forUser($userId)->thisWeek()->get();

        // Monthly VR sessions
        $monthVRSessions = VRSession::forUser($userId)->thisMonth()->get();

        // Most used VR assets
        $popularAssets = VRSession::forUser($userId)
            ->selectRaw('vr_asset_title, COUNT(*) as sessions_count, AVG(session_quality) as avg_quality')
            ->groupBy('vr_asset_title')
            ->orderBy('sessions_count', 'desc')
            ->take(5)
            ->get();

        // Mood improvement analysis
        $sessionsWithMoodData = VRSession::forUser($userId)
            ->whereNotNull('mood_before')
            ->whereNotNull('mood_after')
            ->get();

        $avgMoodImprovement = $sessionsWithMoodData->avg('mood_improvement') ?? 0;

        $analytics = [
            'total_sessions' => $totalSessions,
            'completed_sessions' => $completedSessions,
            'total_duration_minutes' => round($totalDuration / 60, 1),
            'avg_session_quality' => round($avgSessionQuality, 1),
            'week_sessions' => $weekVRSessions->count(),
            'month_sessions' => $monthVRSessions->count(),
            'popular_assets' => $popularAssets,
            'avg_mood_improvement' => round($avgMoodImprovement, 1),
            'sessions_with_mood_data' => $sessionsWithMoodData->count(),
        ];

        return view('vr-analytics', compact('analytics'));
    }
}
