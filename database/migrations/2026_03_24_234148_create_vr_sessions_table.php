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
        Schema::create('vr_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('vr_asset_id');
            $table->string('vr_asset_title');
            $table->integer('session_duration')->nullable(); // in seconds
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('mood_before')->nullable(); // 1-10 scale
            $table->integer('mood_after')->nullable(); // 1-10 scale
            $table->string('device_type')->default('browser'); // VR headset type or browser
            $table->integer('session_quality')->nullable(); // 1-5 rating
            $table->text('notes')->nullable(); // user feedback
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vr_sessions');
    }
};
