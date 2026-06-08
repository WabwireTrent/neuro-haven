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
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, 'user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('assessment_type')->comment('e.g. phq-9, gad-7');
            $table->integer('score');
            $table->string('severity')->comment('none, mild, moderate, moderately-severe, severe');
            $table->json('responses')->comment('JSON of answers');
            $table->datetime('completed_at');
            $table->timestamps();

            $table->index('assessment_type');
            $table->index('severity');
            $table->index('completed_at');
            $table->index(['user_id', 'assessment_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
    }
};