<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mood;
use App\Models\VRSession;
use App\Models\VRAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->whereHas('assignedTherapists', function ($query) {
                $query->where('therapist_id', auth()->id());
            })
            ->withCount(['moods', 'vrSessions'])
            ->with(['moods' => function ($query) {
                $query->latest()->take(1);
            }])
            ->paginate(20);

        return view('therapist.patients', compact('patients'));
    }

    public function reports(Request $request)
    {
        $assignedPatientIds = User::where('role', 'patient')
            ->whereHas('assignedTherapists', function ($query) {
                $query->where('therapist_id', auth()->id());
            })
            ->pluck('id');

        $query = User::whereIn('id', $assignedPatientIds)
            ->select([
                'users.*',
                DB::raw('(SELECT COUNT(*) FROM moods WHERE moods.user_id = users.id) as moods_count'),
                DB::raw('(SELECT ROUND(AVG(mood_scale), 1) FROM moods WHERE moods.user_id = users.id) as avg_mood'),
                DB::raw('(SELECT COUNT(*) FROM vr_sessions WHERE vr_sessions.user_id = users.id) as sessions_count'),
                DB::raw('(SELECT MAX(started_at) FROM vr_sessions WHERE vr_sessions.user_id = users.id) as last_session_at'),
                DB::raw('(SELECT COUNT(*) FROM vr_sessions WHERE vr_sessions.user_id = users.id AND vr_sessions.completed_at IS NOT NULL) as completed_sessions'),
            ]);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'name');
        $dir = $request->input('dir', 'asc');
        $allowed = ['name', 'moods_count', 'avg_mood', 'sessions_count', 'completed_sessions', 'last_session_at', 'created_at'];
        if (in_array($sort, $allowed)) {
            $query->orderBy($sort, $dir === 'desc' ? 'desc' : 'asc');
        }

        $patients = $query->paginate(25)->withQueryString();

        $summary = [
            'total' => $assignedPatientIds->count(),
            'total_moods' => Mood::whereIn('user_id', $assignedPatientIds)->count(),
            'total_sessions' => VRSession::whereIn('user_id', $assignedPatientIds)->count(),
            'avg_mood_all' => round(Mood::whereIn('user_id', $assignedPatientIds)->avg('mood_scale') ?? 0, 1),
        ];

        return view('therapist.reports', compact('patients', 'summary', 'sort', 'dir'));
    }

    public function patientDetails(User $patient)
    {
        if ($patient->role !== 'patient') {
            abort(403, 'Only patient data can be viewed.');
        }

        // Verify this therapist is actually assigned to this patient
        $therapistId = auth()->id();
        $isAssigned = $patient->assignedTherapists()
            ->where('therapist_id', $therapistId)
            ->exists();

        if (!$isAssigned) {
            abort(403, 'You are not assigned to this patient.');
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

    public function vrSessionReport(VRSession $vrSession)
    {
        $patient = $vrSession->user;
        if ($patient->role !== 'patient') abort(403);

        $therapistId = auth()->id();
        $isAssigned = $patient->assignedTherapists()
            ->where('therapist_id', $therapistId)
            ->exists();
        if (!$isAssigned) abort(403, 'You are not assigned to this patient.');

        $patient->loadCount(['moods', 'vrSessions']);

        $recentMoodTrend = $patient->moods()
            ->orderBy('mood_date')
            ->take(14)
            ->get(['mood_date', 'mood_scale']);

        $asset = null;
        if ($vrSession->vr_asset_id) {
            $asset = VRAsset::find($vrSession->vr_asset_id);
        }

        $avgMood = round($patient->moods()->avg('mood_scale') ?? 0, 1);
        $prevSessions = VRSession::forUser($patient->id)
            ->where('id', '!=', $vrSession->id)
            ->completed()
            ->count();
        $totalDuration = VRSession::forUser($patient->id)
            ->completed()
            ->sum('session_duration') ?? 0;
        $avgQuality = round(VRSession::forUser($patient->id)
            ->whereNotNull('session_quality')
            ->avg('session_quality') ?? 0, 1);

        $reportData = [
            'session' => $vrSession,
            'patient' => $patient,
            'asset' => $asset,
            'mood_trend' => $recentMoodTrend,
            'avg_mood' => $avgMood,
            'prev_completed' => $prevSessions,
            'total_duration_all' => $totalDuration,
            'avg_quality_all' => $avgQuality,
            'streak' => $patient->getCurrentStreak() ?? 0,
        ];

        return view('therapist.vr-session-report', $reportData);
    }
}
