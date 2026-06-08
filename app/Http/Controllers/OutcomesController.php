<?php

namespace App\Http\Controllers;

use App\Models\AssessmentResult;
use App\Models\Mood;
use App\Models\VRSession;
use App\Models\User;
use App\Models\CrisisAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutcomesController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isTherapist = $user->isTherapist() || $user->isAdmin();

        if ($isTherapist) {
            $userIds = User::where('role', 'patient')
                ->whereHas('assignedTherapists', function ($q) use ($user) {
                    $q->where('therapist_id', $user->id);
                })
                ->pluck('id');
        } else {
            $userIds = [$user->id];
        }

        $totalPatients = $isTherapist ? count($userIds) : 1;

        $moodData = Mood::whereIn('user_id', $userIds)
            ->selectRaw('mood_date, AVG(mood_scale) as avg_mood, COUNT(*) as entries')
            ->whereBetween('mood_date', [now()->subDays(30), now()])
            ->groupBy('mood_date')
            ->orderBy('mood_date')
            ->get();

        $assessmentScores = AssessmentResult::whereIn('user_id', $userIds)
            ->selectRaw('assessment_type, AVG(score) as avg_score, COUNT(*) as total')
            ->groupBy('assessment_type')
            ->get();

        $totalSessions = VRSession::whereIn('user_id', $userIds)->count();
        $completedSessions = VRSession::whereIn('user_id', $userIds)->whereNotNull('completed_at')->count();
        $completionRate = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100) : 0;

        $activeAlerts = CrisisAlert::whereIn('user_id', $userIds)
            ->where('is_resolved', false)
            ->count();

        $avgMoodAll = Mood::whereIn('user_id', $userIds)->avg('mood_scale') ?? 0;

        $engagementByDay = Mood::whereIn('user_id', $userIds)
            ->selectRaw("strftime('%w', mood_date) as day, COUNT(*) as count")
            ->whereBetween('mood_date', [now()->subDays(30), now()])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        return view('outcomes.index', compact(
            'isTherapist', 'totalPatients', 'moodData', 'assessmentScores',
            'totalSessions', 'completedSessions', 'completionRate',
            'activeAlerts', 'avgMoodAll', 'engagementByDay'
        ));
    }

    public function patientReport(User $patient)
    {
        $moodTrend = Mood::where('user_id', $patient->id)
            ->orderBy('mood_date')
            ->take(30)
            ->get(['mood_date', 'mood_scale', 'mood']);

        $assessments = AssessmentResult::where('user_id', $patient->id)
            ->orderBy('completed_at', 'desc')
            ->get()
            ->groupBy('assessment_type');

        $sessions = VRSession::where('user_id', $patient->id)
            ->orderBy('started_at', 'desc')
            ->take(20)
            ->get();

        $stats = [
            'avg_mood' => round(Mood::where('user_id', $patient->id)->avg('mood_scale') ?? 0, 1),
            'total_sessions' => VRSession::where('user_id', $patient->id)->count(),
            'completed_sessions' => VRSession::where('user_id', $patient->id)->whereNotNull('completed_at')->count(),
            'streak' => $patient->getCurrentStreak(),
            'total_assessments' => AssessmentResult::where('user_id', $patient->id)->count(),
        ];

        return view('outcomes.patient-report', compact('patient', 'moodTrend', 'assessments', 'sessions', 'stats'));
    }
}
