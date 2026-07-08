<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conversation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('message_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('ai_provider_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->longText('response');

            $table->unsignedInteger('response_time_ms')->nullable();
            $table->unsignedInteger('prompt_tokens')->nullable();
            $table->unsignedInteger('completion_tokens')->nullable();
            $table->unsignedInteger('total_tokens')->nullable();

            $table->boolean('is_successful')->default(true);
            $table->text('error_message')->nullable();

            $table->json('raw_response')->nullable();

            $table->timestamps();

            $table->index(['conversation_id', 'ai_provider_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_responses');
    }
};