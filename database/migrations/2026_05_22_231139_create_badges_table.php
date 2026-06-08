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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('e.g. first_mood, streak_7, streak_30, sessions_10, assessments_5, perfect_week');
            $table->string('name');
            $table->text('description');
            $table->string('icon')->comment('Path or emoji representing the badge');
            $table->string('category')->comment('engagement, milestone, clinical, social');
            $table->integer('requirement_value')->nullable()->comment('Numeric threshold to earn this badge');
            $table->timestamps();

            $table->index('category');
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};