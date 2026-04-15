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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('onboarding_completed')->default(false)->after('role');
            $table->string('preferred_mood')->nullable()->after('onboarding_completed');
            $table->json('therapy_concerns')->nullable()->after('preferred_mood');
            $table->string('therapy_preference')->nullable()->after('therapy_concerns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['onboarding_completed', 'preferred_mood', 'therapy_concerns', 'therapy_preference']);
        });
    }
};
