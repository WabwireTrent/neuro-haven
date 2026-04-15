<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('v_r_assets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category'); // Relaxation, Meditation, Inspiration, Breathing, etc.
            $table->integer('duration_minutes'); // Duration in minutes
            $table->string('image_path')->nullable(); // Path to asset image/thumbnail
            $table->string('file_path')->nullable(); // Path to main VR file
            $table->string('file_type')->default('video'); // video, audio, model, interactive
            $table->integer('difficulty_level')->default(1); // 1-5 difficulty scale
            $table->text('therapeutic_benefits')->nullable(); // JSON or text describing benefits
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0); // Track how many times used
            $table->decimal('average_rating', 3, 2)->default(0); // User ratings
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_r_assets');
    }
};
