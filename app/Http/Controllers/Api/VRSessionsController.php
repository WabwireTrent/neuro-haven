<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VRSession;
use Illuminate\Http\Request;

class VRSessionsController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'vr_asset_id' => 'required|integer',
            'vr_asset_title' => 'required|string',
            'mood_before' => 'nullable|integer|min:1|max:10',
            'device_type' => 'required|string'
        ]);

        $session = VRSession::create([
            'user_id' => auth()->id(),
            'vr_asset_id' => $request->vr_asset_id,
            'vr_asset_title' => $request->vr_asset_title,
            'started_at' => now(),
            'mood_before' => $request->mood_before,
            'device_type' => $request->device_type
        ]);

        return response()->json($session);
    }

    public function end(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'session_duration' => 'required|integer',
            'mood_after' => 'nullable|integer|min:1|max:10',
            'session_quality' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        $session = VRSession::where('id', $request->session_id)
                           ->where('user_id', auth()->id())
                           ->firstOrFail();

        $session->update([
            'session_duration' => $request->session_duration,
            'completed_at' => now(),
            'mood_after' => $request->mood_after,
            'session_quality' => $request->session_quality,
            'notes' => $request->notes
        ]);

        return response()->json(['success' => true]);
    }

    public function getCurrentMood()
    {
        $mood = \App\Models\Mood::forUser(auth()->id())
                                ->whereDate('mood_date', today())
                                ->latest()
                                ->first();

        return response()->json(['mood' => $mood]);
    }
}
