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
        Schema::create('moods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('mood', ['excellent', 'happy', 'calm', 'anxious', 'sad', 'stressed'])->default('calm');
            $table->integer('mood_scale')->default(5); // 1-10 scale
            $table->text('note')->nullable();
            $table->date('mood_date')->default(DB::raw('CURRENT_DATE'));
            $table->timestamps();
            $table->index('user_id');
            $table->index('mood_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moods');
    }
};
