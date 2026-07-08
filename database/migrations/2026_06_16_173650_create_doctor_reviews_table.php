<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('conversation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('ai_response_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('ai_provider_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->unsignedTinyInteger('accuracy_score')->nullable();
            $table->unsignedTinyInteger('empathy_score')->nullable();
            $table->unsignedTinyInteger('safety_score')->nullable();
            $table->unsignedTinyInteger('usefulness_score')->nullable();

            $table->enum('risk_level', [
                'low',
                'medium',
                'high',
                'crisis',
            ])->default('low');

            $table->boolean('needs_follow_up')->default(false);
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['doctor_id', 'conversation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_reviews');
    }
};