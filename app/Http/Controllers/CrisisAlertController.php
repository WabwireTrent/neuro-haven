<?php

namespace App\Http\Controllers;

use App\Models\CrisisAlert;
use App\Models\AssessmentResult;
use App\Models\Mood;
use Illuminate\Http\Request;

class CrisisAlertController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->isTherapist() || $user->isAdmin()) {
            $alerts = CrisisAlert::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $alerts = CrisisAlert::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('crisis-alerts.index', compact('alerts'));
    }

    public function resolve(CrisisAlert $crisisAlert)
    {
        $crisisAlert->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Alert resolved.');
    }

    public static function checkAndCreate($userId)
    {
        $created = [];

        // Rule 1: PHQ-9 score >= 15 (moderately-severe or severe depression)
        $latestPhq9 = AssessmentResult::where('user_id', $userId)
            ->where('assessment_type', 'phq-9')
            ->latest('completed_at')
            ->first();

        if ($latestPhq9 && $latestPhq9->score >= 15) {
            $existing = CrisisAlert::where('user_id', $userId)
                ->where('triggered_by', 'assessment_score')
                ->where('is_resolved', false)
                ->exists();

            if (!$existing) {
                $created[] = CrisisAlert::create([
                    'user_id' => $userId,
                    'triggered_by' => 'assessment_score',
                    'severity' => $latestPhq9->score >= 20 ? 'critical' : 'high',
                    'message' => "PHQ-9 score of {$latestPhq9->score} indicates " . ($latestPhq9->score >= 20 ? 'severe' : 'moderately-severe') . " depression.",
                    'details' => "Assessment completed at {$latestPhq9->completed_at}. Severity: {$latestPhq9->severity}",
                ]);
            }
        }

        // Rule 2: Mood drops by 4+ points in 2 days
        $recentMoods = Mood::where('user_id', $userId)
            ->orderBy('mood_date', 'desc')
            ->take(2)
            ->get();

        if ($recentMoods->count() === 2) {
            $drop = $recentMoods->first()->mood_scale - $recentMoods->last()->mood_scale;
            if ($drop <= -4) {
                $existing = CrisisAlert::where('user_id', $userId)
                    ->where('triggered_by', 'mood_drop')
                    ->where('is_resolved', false)
                    ->exists();

                if (!$existing) {
                    $created[] = CrisisAlert::create([
                        'user_id' => $userId,
                        'triggered_by' => 'mood_drop',
                        'severity' => 'high',
                        'message' => "Significant mood drop detected: {$recentMoods->last()->mood_scale} → {$recentMoods->first()->mood_scale}",
                        'details' => "Mood dropped by " . abs($drop) . " points in the last 2 days.",
                    ]);
                }
            }
        }

        // Rule 3: Mood score of 1 (critical low)
        $latestMood = $recentMoods->first();
        if ($latestMood && $latestMood->mood_scale <= 1) {
            $existing = CrisisAlert::where('user_id', $userId)
                ->where('triggered_by', 'mood_drop')
                ->where('is_resolved', false)
                ->exists();

            if (!$existing) {
                $created[] = CrisisAlert::create([
                    'user_id' => $userId,
                    'triggered_by' => 'mood_drop',
                    'severity' => 'critical',
                    'message' => 'Critical mood level detected (1/10). Immediate attention may be needed.',
                    'details' => "Patient reported minimum mood score.",
                ]);
            }
        }

        // Rule 4: PHQ-9 question 9 (self-harm) score > 0
        if ($latestPhq9 && $latestPhq9->responses) {
            $responses = is_array($latestPhq9->responses) ? $latestPhq9->responses : json_decode($latestPhq9->responses, true);
            if (isset($responses[8]['score']) && $responses[8]['score'] > 0) {
                $existing = CrisisAlert::where('user_id', $userId)
                    ->where('triggered_by', 'pattern')
                    ->where('is_resolved', false)
                    ->exists();

                if (!$existing) {
                    $created[] = CrisisAlert::create([
                        'user_id' => $userId,
                        'triggered_by' => 'pattern',
                        'severity' => 'critical',
                        'message' => 'Self-harm thoughts reported in PHQ-9 assessment.',
                        'details' => 'Question 9 (thoughts of self-harm) scored > 0. Immediate review required.',
                    ]);
                }
            }
        }

        return $created;
    }
}
