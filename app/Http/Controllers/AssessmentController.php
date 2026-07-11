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
        $interpretation = $this->getInterpretation($type, $severity);
        $suggestedPlan = $this->getSuggestedPlan($type, $severity);

        $result = AssessmentResult::create([
            'user_id' => auth()->id(),
            'assessment_type' => $type,
            'score' => $totalScore,
            'severity' => $severity,
            'responses' => $responses,
            'interpretation' => $interpretation,
            'suggested_plan' => $suggestedPlan,
            'completed_at' => now(),
        ]);

        return redirect()->route('assessments.report', $result)
            ->with('success', 'Assessment completed successfully.');
    }

    public function results($type)
    {
        $results = AssessmentResult::where('user_id', auth()->id())
            ->where('assessment_type', $type)
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('assessments.results', compact('type', 'results'));
    }

    public function report(AssessmentResult $assessmentResult)
    {
        if ($assessmentResult->user_id !== auth()->id()) {
            abort(403);
        }

        $type = $assessmentResult->assessment_type;
        $maxScore = $this->maxScore($type);

        return view('assessments.report', compact('assessmentResult', 'maxScore'));
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

    private function getInterpretation($type, $severity)
    {
        if ($type === 'phq-9') {
            return match ($severity) {
                'none' => 'Your responses suggest minimal to no depressive symptoms. Continue maintaining healthy habits and self-care routines.',
                'mild' => 'Your responses suggest mild depressive symptoms. Consider incorporating regular exercise, mindfulness practices, and monitoring your mood patterns.',
                'moderate' => 'Your responses suggest moderate depressive symptoms. It is recommended to speak with a mental health professional about therapy options such as Cognitive Behavioral Therapy (CBT).',
                'moderately-severe' => 'Your responses suggest moderately severe depressive symptoms. Professional support is strongly recommended — please consult a mental health provider about therapy and possible medication evaluation.',
                'severe' => 'Your responses suggest severe depressive symptoms. Please seek immediate support from a mental health professional. If you are having thoughts of self-harm, contact emergency services or a crisis hotline immediately.',
                default => 'Review your responses and consult with a healthcare professional for a complete evaluation.',
            };
        }

        return match ($severity) {
            'none' => 'Your responses suggest minimal to no anxiety symptoms. Continue practicing good stress management and self-care.',
            'mild' => 'Your responses suggest mild anxiety symptoms. Consider incorporating relaxation techniques, deep breathing exercises, and regular physical activity.',
            'moderate' => 'Your responses suggest moderate anxiety symptoms. Speaking with a therapist about anxiety management strategies, including CBT or mindfulness-based approaches, is recommended.',
            'severe' => 'Your responses suggest severe anxiety symptoms. Professional support is strongly recommended — please consult a mental health provider about therapy options and possible medication evaluation.',
            default => 'Review your responses and consult with a healthcare professional for a complete evaluation.',
        };
    }

    private function getSuggestedPlan($type, $severity)
    {
        if ($severity === 'none') {
            return [
                'title' => 'Wellness Maintenance',
                'description' => 'Continue your current wellness practices. No clinical intervention is indicated at this time.',
                'goals' => [
                    'Maintain regular sleep schedule (7-9 hours per night)',
                    'Engage in physical activity at least 3 times per week',
                    'Practice mindfulness or meditation for 10 minutes daily',
                    'Keep a mood journal to track emotional patterns',
                ],
                'recommended_frequency' => 'As needed',
            ];
        }

        if ($type === 'phq-9') {
            return match ($severity) {
                'mild' => [
                    'title' => 'Mild Depression — Lifestyle & Monitoring Plan',
                    'description' => 'A structured plan focusing on lifestyle modifications and mood tracking to address mild depressive symptoms.',
                    'goals' => [
                        'Establish a consistent daily routine with set wake/sleep times',
                        'Incorporate 30 minutes of moderate exercise 5 days per week',
                        'Practice mindfulness meditation for 10-15 minutes daily',
                        'Log mood daily and identify triggers or patterns',
                        'Reduce screen time before bed and improve sleep hygiene',
                    ],
                    'recommended_frequency' => 'Self-directed daily practices. Re-assess in 4 weeks.',
                ],
                'moderate' => [
                    'title' => 'Moderate Depression — Therapy & Lifestyle Intervention',
                    'description' => 'A combined approach of professional therapy and lifestyle changes to address moderate depressive symptoms.',
                    'goals' => [
                        'Begin Cognitive Behavioral Therapy (CBT) with a licensed therapist',
                        'Attend therapy sessions weekly for at least 8 weeks',
                        'Establish a consistent exercise routine (30 min, 5x/week)',
                        'Practice behavioral activation — schedule one enjoyable activity daily',
                        'Implement sleep hygiene protocols (consistent bed/wake times)',
                        'Reduce alcohol and caffeine consumption',
                    ],
                    'recommended_frequency' => 'Weekly therapy sessions + daily self-care practices. Re-assess in 8 weeks.',
                ],
                'moderately-severe' => [
                    'title' => 'Moderately Severe Depression — Intensive Therapy & Medical Evaluation',
                    'description' => 'An intensive treatment plan requiring professional therapy and consideration of medication evaluation.',
                    'goals' => [
                        'Schedule an appointment with a psychiatrist for medication evaluation',
                        'Begin or continue weekly therapy (CBT or interpersonal therapy)',
                        'Establish a daily routine with regular meals and sleep schedule',
                        'Engage in gentle physical activity (walking, yoga) for 20 minutes daily',
                        'Build a support network — share your plan with a trusted person',
                        'Use crisis resources if symptoms worsen or thoughts of self-harm occur',
                    ],
                    'recommended_frequency' => 'Weekly therapy + psychiatric consultation. Re-assess in 4-6 weeks.',
                ],
                'severe' => [
                    'title' => 'Severe Depression — Urgent Care & Intensive Treatment',
                    'description' => 'Immediate professional intervention is needed. Please seek help right away.',
                    'goals' => [
                        'Contact a mental health crisis line or emergency services if at immediate risk',
                        'Schedule an urgent appointment with a psychiatrist',
                        'Begin intensive therapy (weekly or bi-weekly sessions)',
                        'Establish a safety plan with your therapist',
                        'Engage a support person to check in daily',
                        'Follow medication regimen as prescribed by psychiatrist',
                    ],
                    'recommended_frequency' => 'Urgent. Weekly therapy + psychiatric follow-up. Crisis resources available 24/7.',
                ],
            };
        }

        return match ($severity) {
            'mild' => [
                'title' => 'Mild Anxiety — Relaxation & Mindfulness Plan',
                'description' => 'A self-guided plan focusing on relaxation techniques and stress management for mild anxiety.',
                'goals' => [
                    'Practice diaphragmatic breathing for 5 minutes, 3 times daily',
                    'Incorporate progressive muscle relaxation before bed',
                    'Engage in 20 minutes of moderate exercise daily',
                    'Limit caffeine intake to morning hours only',
                    'Use a worry journal to externalize anxious thoughts',
                ],
                'recommended_frequency' => 'Daily practices. Re-assess in 4 weeks.',
            ],
            'moderate' => [
                'title' => 'Moderate Anxiety — Therapy & Coping Strategies',
                'description' => 'Professional therapy combined with evidence-based coping strategies for moderate anxiety.',
                'goals' => [
                    'Begin therapy with a CBT specialist for anxiety',
                    'Attend weekly therapy sessions for at least 8 weeks',
                    'Practice daily mindfulness meditation (10-15 minutes)',
                    'Learn and apply cognitive restructuring techniques',
                    'Establish a consistent sleep schedule (7-9 hours)',
                    'Reduce avoidance behaviors gradually with therapist guidance',
                ],
                'recommended_frequency' => 'Weekly therapy + daily coping practices. Re-assess in 8 weeks.',
            ],
            'severe' => [
                'title' => 'Severe Anxiety — Comprehensive Treatment Plan',
                'description' => 'Intensive treatment requiring therapy, possible medication, and structured coping strategies.',
                'goals' => [
                    'Consult with a psychiatrist regarding medication options',
                    'Begin intensive therapy (weekly sessions minimum)',
                    'Practice grounding techniques during acute anxiety episodes',
                    'Establish a daily routine with predictable structure',
                    'Reduce sources of stress where possible',
                    'Build a crisis plan with your therapist for panic episodes',
                ],
                'recommended_frequency' => 'Weekly therapy + psychiatric consultation. Re-assess in 4-6 weeks.',
            ],
        };
    }
}
