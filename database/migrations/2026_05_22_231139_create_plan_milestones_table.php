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
        Schema::create('plan_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\TreatmentPlan::class, 'treatment_plan_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('due_date');
            $table->datetime('completed_at')->nullable();
            $table->integer('position')->default(0)->comment('Ordering of milestones');
            $table->timestamps();

            $table->index('treatment_plan_id');
            $table->index('position');
            $table->index(['treatment_plan_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_milestones');
    }
};