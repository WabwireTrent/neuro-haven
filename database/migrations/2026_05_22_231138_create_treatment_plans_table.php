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
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, 'patient_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\User::class, 'therapist_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('goals');
            $table->string('status')->default('active')->comment('active, completed, on-hold, cancelled');
            $table->datetime('started_at')->nullable();
            $table->datetime('target_end_date')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('patient_id');
            $table->index('therapist_id');
            $table->index(['patient_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plans');
    }
};