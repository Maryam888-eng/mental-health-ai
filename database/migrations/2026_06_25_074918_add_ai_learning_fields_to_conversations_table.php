<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {

            // User ka current stress level
            $table->integer('stress_score')
                ->default(0)
                ->after('summary');

            // Last detected mood
            $table->string('last_detected_mood')
                ->nullable()
                ->after('stress_score');

            // User preferences JSON format mein
            $table->json('user_preferences')
                ->nullable()
                ->after('last_detected_mood');
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn([
                'stress_score',
                'last_detected_mood',
                'user_preferences',
            ]);
        });
    }
};