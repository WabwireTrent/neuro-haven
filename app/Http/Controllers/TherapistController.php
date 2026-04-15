<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mood;
use App\Models\VRSession;
use Illuminate\Http\Request;

class TherapistController extends Controller
{
    public function dashboard()
    {
        $therapistId = auth()->id();

        // Get ONLY patients assigned to this therapist
        $patients = User::where('role', 'patient')
            ->whereHas('assignedTherapists', function ($query) use ($therapistId) {
                $query->where('therapist_id', $therapistId);
            })
            ->withCount(['moods', 'vrSessions'])
            ->with(['moods' => function ($query) {
                $query->latest()->take(1);
            }])
            ->paginate(12);

        // Therapist's personal analytics
        $totalPatients = $patients->total();
        $activePatientsThisWeek = User::where('role', 'patient')
            ->whereHas('assignedTherapists', function ($query) use ($therapistId) {
                $query->where('therapist_id', $therapistId);
            })
            ->whereHas('vrSessions', function ($query) {
                $query->whereBetween('started_at', [now()->startOfWeek(), now()->endOfWeek()]);
            })
            ->count();

        // Recent patient activities (from assigned patients only)
        $assignedPatientIds = User::where('role', 'patient')
            ->whereHas('assignedTherapists', function ($query) use ($therapistId) {
                $query->where('therapist_id', $therapistId);
            })
            ->pluck('id');

        $recentMoods = Mood::with('user')
            ->whereIn('user_id', $assignedPatientIds)
            ->latest()
            ->take(10)
            ->get();

        $recentVRSessions = VRSession::with('user')
            ->whereIn('user_id', $assignedPatientIds)
            ->latest()
            ->take(10)
            ->get();

        // Most used VR assets by assigned patients
        $popularAssets = VRSession::whereIn('user_id', $assignedPatientIds)
            ->selectRaw('vr_asset_title, COUNT(*) as sessions_count, AVG(session_quality) as avg_quality')
            ->groupBy('vr_asset_title')
            ->orderBy('sessions_count', 'desc')
            ->take(5)
            ->get();

        // Average mood trends for assigned patients
        $avgMoodToday = Mood::whereIn('user_id', $assignedPatientIds)
            ->whereDate('mood_date', today())
            ->avg('mood_scale') ?? 0;
        $avgMoodWeek = Mood::whereIn('user_id', $assignedPatientIds)
            ->whereBetween('mood_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->avg('mood_scale') ?? 0;

        $analytics = [
            'total_patients' => $totalPatients,
            'active_patients_week' => $activePatientsThisWeek,
            'recent_moods' => $recentMoods,
            'recent_vr_sessions' => $recentVRSessions,
            'popular_assets' => $popularAssets,
            'avg_mood_today' => round($avgMoodToday, 1),
            'avg_mood_week' => round($avgMoodWeek, 1),
        ];

        return view('therapist.dashboard', compact('patients', 'analytics'));
    }

    public function patients()
    {
        $patients = User::where('role', 'patient')
            ->withCount(['moods', 'vrSessions'])
            ->with(['moods' => function ($query) {
                $query->latest()->take(1);
            }])
            ->paginate(20);

        return view('therapist.patients', compact('patients'));
    }

    public function patientDetails(User $patient)
    {
        if ($patient->role !== 'patient') {
            abort(403, 'Only patient data can be viewed.');
        }

        $patient->load([
            'moods' => function ($query) {
                $query->orderBy('mood_date', 'desc')->take(30);
            },
            'vrSessions' => function ($query) {
                $query->orderBy('started_at', 'desc')->take(20);
            }
        ]);

        $moodStats = [
            'total_moods' => $patient->moods->count(),
            'avg_mood' => round($patient->moods->avg('mood_scale') ?? 0, 1),
            'week_moods' => $patient->moods()->whereBetween('mood_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month_moods' => $patient->moods()->whereBetween('mood_date', [now()->startOfMonth(), now()->endOfMonth()])->count(),
        ];

        $vrStats = [
            'total_sessions' => $patient->vrSessions->count(),
            'completed_sessions' => $patient->vrSessions->whereNotNull('completed_at')->count(),
            'total_duration' => $patient->vrSessions->sum('session_duration') ?? 0,
            'avg_quality' => round($patient->vrSessions->whereNotNull('session_quality')->avg('session_quality') ?? 0, 1),
        ];

        $moodTrend = $patient->moods()
            ->orderBy('mood_date')
            ->take(14)
            ->get(['mood_date', 'mood_scale']);

        return view('therapist.patient-details', compact('patient', 'moodStats', 'vrStats', 'moodTrend'));
    }
}
