<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VRAsset;
use App\Models\VRSession;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\UnityLauncherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UnityBridgeController extends Controller
{
    protected UnityLauncherService $unityLauncher;

    public function __construct(UnityLauncherService $unityLauncher)
    {
        $this->unityLauncher = $unityLauncher;
    }

    public function launch(Request $request)
    {
        $request->validate([
            'vr_asset_id' => 'required|integer|exists:v_r_assets,id',
            'duration_minutes' => 'nullable|integer|min:1|max:120',
        ]);

        $user = $request->user();
        $asset = VRAsset::findOrFail($request->vr_asset_id);

        $durationMinutes = $request->duration_minutes ?? ($asset->duration_minutes ?? 10);
        $durationSeconds = $durationMinutes * 60;

        // Map the asset to a Unity scene key
        $sceneKey = $this->mapAssetToScene($asset);

        // 1. Create a VRSession record in Laravel
        $session = VRSession::create([
            'user_id' => $user->id,
            'vr_asset_id' => $asset->id,
            'vr_asset_title' => $asset->title,
            'started_at' => now(),
            'mood_before' => $request->mood_before,
            'device_type' => 'meta-quest-unity',
        ]);

        // 2. Notify the Node.js backend to set the current session
        $backendUrl = config('services.backend.url', 'http://localhost:3000');
        try {
            $response = Http::timeout(5)->post("{$backendUrl}/start-session", [
                'scene' => $sceneKey,
                'duration' => $durationSeconds,
                'session_id' => $session->id,
                'user_id' => $user->id,
            ]);

            if (!$response->successful()) {
                Log::warning("[UnityBridge] Backend session start returned: {$response->status()}");
            }
        } catch (\Exception $e) {
            Log::error("[UnityBridge] Failed to contact backend: {$e->getMessage()}");
        }

        // 3. Increment asset usage count
        $asset->increment('usage_count');

        // 4. Launch the Unity executable
        $launchResult = $this->unityLauncher->launchViaPowerShell($sceneKey, $durationSeconds, [
            'session_id' => $session->id,
            'user_id' => $user->id,
        ]);

        if (!$launchResult['success']) {
            $session->update([
                'completed_at' => now(),
                'notes' => "Unity launch failed: {$launchResult['error']}",
            ]);

            return response()->json([
                'success' => false,
                'error' => $launchResult['error'],
                'session' => $session,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Unity VR experience launching...',
            'session' => $session,
            'scene_key' => $sceneKey,
            'duration_seconds' => $durationSeconds,
            'launch' => $launchResult,
        ]);
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer|exists:vr_sessions,id',
        ]);

        $session = VRSession::where('id', $request->session_id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // Check with the Node.js backend for real-time status
        $backendUrl = config('services.backend.url', 'http://localhost:3000');
        $unityStatus = 'unknown';

        try {
            $response = Http::timeout(3)->get("{$backendUrl}/current-session");
            if ($response->successful()) {
                $data = $response->json();
                $unityStatus = $data['status'] ?? 'unknown';
            }
        } catch (\Exception $e) {
            $unityStatus = 'backend-unreachable';
        }

        return response()->json([
            'session' => $session,
            'unity_status' => $unityStatus,
            'is_completed' => !is_null($session->completed_at),
        ]);
    }

    public function scenes(Request $request)
    {
        $scenes = VRAsset::active()->get()->map(function ($asset) {
            return [
                'id' => $asset->id,
                'title' => $asset->title,
                'scene_key' => $this->mapAssetToScene($asset),
                'duration_minutes' => $asset->duration_minutes ?? 10,
                'category' => $asset->category,
                'scene_name' => $asset->file_path ?? $asset->title,
            ];
        });

        return response()->json($scenes);
    }

    protected function mapAssetToScene($asset): string
    {
        $categoryMap = [
            'Relaxation' => 'forest',
            'Meditation' => 'beach',
            'Inspiration' => 'mountain',
            'Breathing' => 'breathing',
            'Nature' => 'forest',
            'Mindfulness' => 'beach',
            'Therapy' => 'mountain',
        ];

        return $categoryMap[$asset->category] ?? 'forest';
    }

    public function endSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer|exists:vr_sessions,id',
            'session_duration' => 'required|integer',
            'mood_after' => 'nullable|integer|min:1|max:10',
            'session_quality' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $session = VRSession::where('id', $request->session_id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $session->update([
            'session_duration' => $request->session_duration,
            'completed_at' => now(),
            'mood_after' => $request->mood_after,
            'session_quality' => $request->session_quality,
            'notes' => $request->notes,
        ]);

        // Notify backend to end session
        $backendUrl = config('services.backend.url', 'http://localhost:3000');
        try {
            Http::timeout(3)->get("{$backendUrl}/end-session");
        } catch (\Exception $e) {
            Log::warning("[UnityBridge] Failed to notify backend of session end: {$e->getMessage()}");
        }

        // Notify the patient's therapist about the completed session
        try {
            $patient = $request->user();
            $notifier = app(NotificationService::class);
            $therapist = $notifier->getPatientTherapist($patient);
            if ($therapist) {
                $notifier->notifyVRSessionReport($therapist, $patient, $session);
            }
        } catch (\Exception $e) {
            Log::error("[UnityBridge] Failed to notify therapist: {$e->getMessage()}");
        }

        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }

    public function config(Request $request)
    {
        return response()->json([
            'backend_url' => config('services.backend.url', 'http://localhost:3000'),
            'unity_configured' => !empty(config('services.unity.executable_path')),
            'unity_path' => config('services.unity.executable_path'),
        ]);
    }
}
