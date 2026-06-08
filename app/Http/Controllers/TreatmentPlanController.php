<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use App\Models\TreatmentPlan;
use App\Models\PlanMilestone;
use App\Models\User;
use App\Models\VRAsset;
use Illuminate\Http\Request;

class TreatmentPlanController extends Controller
{
    private array $moodRecommendations = [
        'excellent' => ['category' => 'Inspiration', 'reason' => 'Keep riding that positive energy with an uplifting experience.'],
        'happy' => ['category' => 'Nature', 'reason' => 'Celebrate your good vibes with a beautiful escape into nature.'],
        'calm' => ['category' => 'Meditation', 'reason' => 'Deepen your sense of peace with a guided meditation session.'],
        'anxious' => ['category' => 'Breathing', 'reason' => 'Ease your anxiety with a guided breathing exercise in a serene environment.'],
        'sad' => ['category' => 'Inspiration', 'reason' => 'Lift your spirits with an inspiring mountain view experience.'],
        'stressed' => ['category' => 'Relaxation', 'reason' => 'Release your stress with a calming virtual relaxation session.'],
    ];

    public function index()
    {
        $user = auth()->user();
        if ($user->isTherapist()) {
            $plans = TreatmentPlan::where('therapist_id', $user->id)
                ->with(['patient', 'milestones'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            $plans = TreatmentPlan::where('patient_id', $user->id)
                ->with(['therapist', 'milestones'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        $suggestedSession = null;
        $suggestionReason = null;
        if ($user->isPatient()) {
            $latestMood = Mood::forUser($user->id)->latest()->first();
            if ($latestMood) {
                $recommendation = $this->moodRecommendations[$latestMood->mood] ?? null;
                if ($recommendation) {
                    $asset = VRAsset::active()->byCategory($recommendation['category'])->inRandomOrder()->first();
                    if ($asset) {
                        $suggestedSession = $asset;
                        $suggestionReason = $recommendation['reason'];
                    }
                }
            }
        }

        return view('treatment-plans.index', compact('plans', 'suggestedSession', 'suggestionReason'));
    }

    public function create()
    {
        $user = auth()->user();
        $patients = User::where('role', 'patient')
            ->whereHas('assignedTherapists', function ($q) use ($user) {
                $q->where('therapist_id', $user->id);
            })
            ->get();

        return view('treatment-plans.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goals' => 'required|string',
            'target_end_date' => 'nullable|date',
        ]);

        $data['therapist_id'] = auth()->id();
        $data['status'] = 'active';
        $data['started_at'] = now();

        $plan = TreatmentPlan::create($data);

        return redirect()->route('therapist.treatment-plans.show', $plan)
            ->with('success', 'Treatment plan created.');
    }

    public function show(TreatmentPlan $treatmentPlan)
    {
        $treatmentPlan->load(['patient', 'therapist', 'milestones']);
        return view('treatment-plans.show', compact('treatmentPlan'));
    }

    public function addMilestone(Request $request, TreatmentPlan $treatmentPlan)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $data['position'] = $treatmentPlan->milestones()->count();

        $treatmentPlan->milestones()->create($data);

        return back()->with('success', 'Milestone added.');
    }

    public function completeMilestone(PlanMilestone $planMilestone)
    {
        $planMilestone->update(['completed_at' => now()]);
        return back()->with('success', 'Milestone completed.');
    }

    public function updateStatus(Request $request, TreatmentPlan $treatmentPlan)
    {
        $request->validate(['status' => 'required|in:active,completed,on-hold,cancelled']);

        $data = ['status' => $request->status];
        if ($request->status === 'completed') {
            $data['completed_at'] = now();
        }

        $treatmentPlan->update($data);
        return back()->with('success', 'Plan status updated.');
    }
}
