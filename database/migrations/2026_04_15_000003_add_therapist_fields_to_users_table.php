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
            // Therapist-specific fields
            $table->string('license_number')->nullable()->unique()->after('role');
            $table->string('specialization')->nullable()->after('license_number');
            $table->integer('years_of_experience')->nullable()->after('specialization');
            $table->text('bio')->nullable()->after('years_of_experience');
            
            // Status fields for therapist verification
            $table->string('therapist_status')->default('pending')->nullable()->after('bio'); // pending, approved, rejected
            $table->timestamp('verified_at')->nullable()->after('therapist_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['license_number', 'specialization', 'years_of_experience', 'bio', 'therapist_status', 'verified_at']);
        });
    }
};
