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
        Schema::create('crisis_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, 'user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('triggered_by')->comment('mood_drop, assessment_score, streak_break, pattern');
            $table->string('severity')->comment('low, medium, high, critical');
            $table->string('message');
            $table->text('details')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->datetime('resolved_at')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'resolved_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->index('triggered_by');
            $table->index('severity');
            $table->index('is_resolved');
            $table->index('user_id');
            $table->index(['user_id', 'is_resolved']);
            $table->index(['severity', 'is_resolved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crisis_alerts');
    }
};