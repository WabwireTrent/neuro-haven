<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['key' => 'first_mood', 'name' => 'First Step', 'description' => 'Logged your first mood entry', 'icon' => 'star', 'category' => 'engagement', 'requirement_value' => 1],
            ['key' => 'streak_3', 'name' => 'Consistency Starter', 'description' => '3-day mood logging streak', 'icon' => 'flame', 'category' => 'engagement', 'requirement_value' => 3],
            ['key' => 'streak_7', 'name' => 'Week Warrior', 'description' => '7-day mood logging streak', 'icon' => 'zap', 'category' => 'engagement', 'requirement_value' => 7],
            ['key' => 'streak_30', 'name' => 'Monthly Master', 'description' => '30-day mood logging streak', 'icon' => 'crown', 'category' => 'milestone', 'requirement_value' => 30],
            ['key' => 'sessions_5', 'name' => 'VR Explorer', 'description' => 'Completed 5 VR therapy sessions', 'icon' => 'headphones', 'category' => 'clinical', 'requirement_value' => 5],
            ['key' => 'sessions_20', 'name' => 'VR Veteran', 'description' => 'Completed 20 VR therapy sessions', 'icon' => 'trophy', 'category' => 'clinical', 'requirement_value' => 20],
            ['key' => 'sessions_50', 'name' => 'VR Champion', 'description' => 'Completed 50 VR therapy sessions', 'icon' => 'diamond', 'category' => 'milestone', 'requirement_value' => 50],
            ['key' => 'assessments_3', 'name' => 'Self-Aware', 'description' => 'Completed 3 clinical assessments', 'icon' => 'chart', 'category' => 'clinical', 'requirement_value' => 3],
            ['key' => 'perfect_week', 'name' => 'Perfect Week', 'description' => 'Logged mood every day for a week', 'icon' => 'star-filled', 'category' => 'milestone', 'requirement_value' => 7],
            ['key' => 'first_session', 'name' => 'First Dive', 'description' => 'Completed your first VR session', 'icon' => 'rocket', 'category' => 'engagement', 'requirement_value' => 1],
            ['key' => 'mood_improver', 'name' => 'Mood Improver', 'description' => 'Average mood improved over 2 weeks', 'icon' => 'trending-up', 'category' => 'clinical', 'requirement_value' => null],
            ['key' => 'resilient', 'name' => 'Resilient', 'description' => 'Recovered from a low mood streak', 'icon' => 'shield', 'category' => 'milestone', 'requirement_value' => null],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
