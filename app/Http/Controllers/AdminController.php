<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mood;
use App\Models\VRSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        // System-wide analytics
        $totalUsers = User::count();
        $totalMoods = Mood::count();
        $totalVRSessions = VRSession::count();
        $activeUsersThisWeek = User::whereHas('moods', function ($query) {
            $query->whereBetween('mood_date', [now()->startOfWeek(), now()->endOfWeek()]);
        })->count();

        // User distribution by role
        $userRoles = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');

        // Recent activity
        $recentUsers = User::latest()->take(5)->get();
        $recentMoods = Mood::with('user')->latest()->take(10)->get();
        $recentVRSessions = VRSession::with('user')->latest()->take(10)->get();

        // VR Analytics
        $popularVRAssets = VRSession::selectRaw('vr_asset_title, COUNT(*) as sessions_count')
            ->groupBy('vr_asset_title')
            ->orderBy('sessions_count', 'desc')
            ->take(5)
            ->get();

        $analytics = [
            'total_users' => $totalUsers,
            'total_moods' => $totalMoods,
            'total_vr_sessions' => $totalVRSessions,
            'active_users_week' => $activeUsersThisWeek,
            'user_roles' => $userRoles,
            'recent_users' => $recentUsers,
            'recent_moods' => $recentMoods,
            'recent_vr_sessions' => $recentVRSessions,
            'popular_vr_assets' => $popularVRAssets,
        ];

        return view('admin.dashboard', compact('analytics'));
    }

    public function users()
    {
        $users = User::withCount(['moods', 'vrSessions'])
            ->with(['moods' => function ($query) {
                $query->latest()->take(1);
            }])
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:patient,therapist,admin'
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function userDetails(User $user)
    {
        $user->load(['moods' => function ($query) {
            $query->orderBy('mood_date', 'desc')->take(30);
        }, 'vrSessions' => function ($query) {
            $query->orderBy('started_at', 'desc')->take(20);
        }]);

        // User analytics
        $moodStats = [
            'total_moods' => $user->moods->count(),
            'avg_mood' => $user->moods->avg('mood_scale') ?? 0,
            'week_moods' => $user->moods()->thisWeek()->count(),
            'month_moods' => $user->moods()->thisMonth()->count(),
        ];

        $vrStats = [
            'total_sessions' => $user->vrSessions->count(),
            'completed_sessions' => $user->vrSessions->whereNotNull('completed_at')->count(),
            'total_duration' => $user->vrSessions->sum('session_duration') ?? 0,
            'avg_quality' => $user->vrSessions->whereNotNull('session_quality')->avg('session_quality') ?? 0,
        ];

        return view('admin.user-details', compact('user', 'moodStats', 'vrStats'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:patient,therapist,admin'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function deleteUser(User $user)
    {
        // Prevent deleting the current admin user
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}
