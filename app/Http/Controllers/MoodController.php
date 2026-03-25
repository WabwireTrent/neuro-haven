<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood' => 'required|in:excellent,happy,calm,anxious,sad,stressed',
            'mood_scale' => 'required|integer|min:1|max:10',
            'note' => 'nullable|string|max:1000',
        ]);

        Mood::create([
            'user_id' => auth()->id(),
            'mood' => $validated['mood'],
            'mood_scale' => $validated['mood_scale'],
            'note' => $validated['note'] ?? null,
            'mood_date' => today(),
        ]);

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
