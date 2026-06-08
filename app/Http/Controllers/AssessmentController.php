<?php

namespace App\Http\Controllers;

use App\Models\AssessmentResult;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index()
    {
        $results = AssessmentResult::where('user_id', auth()->id())
            ->orderBy('completed_at', 'desc')
            ->get()
            ->groupBy('assessment_type');

        $latest = AssessmentResult::where('user_id', auth()->id())
            ->latest('completed_at')
            ->first();

        return view('assessments.index', compact('results', 'latest'));
    }

    public function show($type)
    {
        $valid = ['phq-9', 'gad-7'];
        if (!in_array($type, $valid)) {
            abort(404);
        }

        $questions = $this->getQuestions($type);
        $previous = AssessmentResult::where('user_id', auth()->id())
            ->where('assessment_type', $type)
            ->latest('completed_at')
            ->first();

        return view('assessments.take', compact('type', 'questions', 'previous'));
    }

    public function store(Request $request)
    {
        $type = $request->input('assessment_type');
        $valid = ['phq-9', 'gad-7'];
        if (!in_array($type, $valid)) {
            return back()->withErrors(['assessment_type' => 'Invalid assessment type.']);
        }

        $questions = $this->getQuestions($type);
        $responses = [];
        $totalScore = 0;

        foreach ($questions as $i => $question) {
            $score = (int) $request->input("q_{$i}", 0);
            $responses[] = ['question' => $question, 'score' => $score];
            $totalScore += $score;
        }

        $severity = $this->calculateSeverity($type, $totalScore);

        AssessmentResult::create([
            'user_id' => auth()->id(),
            'assessment_type' => $type,
            'score' => $totalScore,
            'severity' => $severity,
            'responses' => $responses,
            'completed_at' => now(),
        ]);

        $labels = ['phq-9' => 'PHQ-9', 'gad-7' => 'GAD-7'];

        return redirect()->route('assessments.results', $type)
            ->with('success', "{$labels[$type]} completed. Your score: {$totalScore}/{$this->maxScore($type)} ({$severity}).");
    }

    public function results($type)
    {
        $results = AssessmentResult::where('user_id', auth()->id())
            ->where('assessment_type', $type)
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('assessments.results', compact('type', 'results'));
    }

    public function history()
    {
        $results = AssessmentResult::where('user_id', auth()->id())
            ->orderBy('completed_at', 'desc')
            ->paginate(20);

        return view('assessments.history', compact('results'));
    }

    private function getQuestions($type)
    {
        if ($type === 'phq-9') {
            return [
                'Little interest or pleasure in doing things',
                'Feeling down, depressed, or hopeless',
                'Trouble falling/staying asleep or sleeping too much',
                'Feeling tired or having little energy',
                'Poor appetite or overeating',
                'Feeling bad about yourself — or that you are a failure',
                'Trouble concentrating on things',
                'Moving/speaking slowly or being fidgety/restless',
                'Thoughts of self-harm or being better off dead',
            ];
        }

        return [
            'Feeling nervous, anxious, or on edge',
            'Not being able to stop or control worrying',
            'Worrying too much about different things',
            'Trouble relaxing',
            'Being so restless that it\'s hard to sit still',
            'Becoming easily annoyed or irritable',
            'Feeling afraid as if something awful might happen',
        ];
    }

    private function maxScore($type)
    {
        return $type === 'phq-9' ? 27 : 21;
    }

    private function calculateSeverity($type, $score)
    {
        if ($type === 'phq-9') {
            if ($score <= 4) return 'none';
            if ($score <= 9) return 'mild';
            if ($score <= 14) return 'moderate';
            if ($score <= 19) return 'moderately-severe';
            return 'severe';
        }

        if ($score <= 4) return 'none';
        if ($score <= 9) return 'mild';
        if ($score <= 14) return 'moderate';
        return 'severe';
    }
}
