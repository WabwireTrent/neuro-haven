<?php

namespace Database\Seeders;

use App\Models\VRAsset;
use Illuminate\Database\Seeder;

class VRAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assets = [
            [
                'title' => 'Peaceful Forest Walk',
                'description' => 'A calming virtual forest experience to reduce stress and anxiety. Walk through serene woodlands with gentle natural sounds.',
                'category' => 'Relaxation',
                'duration_minutes' => 10,
                'file_type' => 'video',
                'difficulty_level' => 1,
                'therapeutic_benefits' => 'Stress relief, nature connection, anxiety reduction',
                'is_active' => true,
            ],
            [
                'title' => 'Ocean Meditation',
                'description' => 'Meditate by the virtual ocean waves for mindfulness and tranquility. Experience the soothing rhythm of waves on a pristine beach.',
                'category' => 'Meditation',
                'duration_minutes' => 15,
                'file_type' => 'audio',
                'difficulty_level' => 2,
                'therapeutic_benefits' => 'Mindfulness, calm, focus improvement',
                'is_active' => true,
            ],
            [
                'title' => 'Mountain View Therapy',
                'description' => 'Experience breathtaking mountain vistas to promote positive thinking and perspective. See the world from new heights.',
                'category' => 'Inspiration',
                'duration_minutes' => 12,
                'file_type' => 'video',
                'difficulty_level' => 2,
                'therapeutic_benefits' => 'Positive mood, perspective shift, motivation',
                'is_active' => true,
            ],
            [
                'title' => 'Guided Breathing Exercise',
                'description' => 'Interactive breathing exercises in a serene virtual environment. Learn proper breathing techniques for anxiety management.',
                'category' => 'Breathing',
                'duration_minutes' => 8,
                'file_type' => 'interactive',
                'difficulty_level' => 1,
                'therapeutic_benefits' => 'Anxiety management, breathing control, relaxation',
                'is_active' => true,
            ],
            [
                'title' => 'Starry Night Relaxation',
                'description' => 'Gaze at a beautiful night sky filled with stars for deep relaxation. Experience the vastness of the universe from your safe space.',
                'category' => 'Relaxation',
                'duration_minutes' => 20,
                'file_type' => 'video',
                'difficulty_level' => 2,
                'therapeutic_benefits' => 'Deep relaxation, perspective, contemplation',
                'is_active' => true,
            ],
            [
                'title' => 'Zen Garden Meditation',
                'description' => 'Find peace in a traditional Japanese zen garden setting. Explore carefully arranged stones, water features, and peaceful pathways.',
                'category' => 'Meditation',
                'duration_minutes' => 18,
                'file_type' => 'interactive',
                'difficulty_level' => 2,
                'therapeutic_benefits' => 'Inner peace, balance, mindfulness',
                'is_active' => true,
            ],
            [
                'title' => 'Tropical Beach Escape',
                'description' => 'Escape to a serene tropical beach with white sand and crystal-clear waters. Perfect for mental reset and stress relief.',
                'category' => 'Nature',
                'duration_minutes' => 15,
                'file_type' => 'video',
                'difficulty_level' => 1,
                'therapeutic_benefits' => 'Stress relief, vacation feeling, joy',
                'is_active' => true,
            ],
            [
                'title' => 'Rainforest Mindfulness',
                'description' => 'Immerse yourself in a lush rainforest with ambient sounds. Discover the biodiversity and tranquility of nature.',
                'category' => 'Mindfulness',
                'duration_minutes' => 12,
                'file_type' => 'audio',
                'difficulty_level' => 2,
                'therapeutic_benefits' => 'Awareness, nature connection, mental clarity',
                'is_active' => true,
            ],
        ];

        foreach ($assets as $asset) {
            VRAsset::create($asset);
        }
    }
}
