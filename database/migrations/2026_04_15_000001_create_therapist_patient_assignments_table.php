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
        Schema::create('therapist_patient_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('active'); // active, inactive, pending
            $table->text('notes')->nullable();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();

            // Ensure unique assignments
            $table->unique(['therapist_id', 'patient_id']);
            $table->index(['therapist_id', 'status']);
            $table->index(['patient_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapist_patient_assignments');
    }
};
