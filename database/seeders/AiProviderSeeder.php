<?php

namespace Database\Seeders;

use App\Models\AiProvider;
use Illuminate\Database\Seeder;

class AiProviderSeeder extends Seeder
{
    public function run(): void
    {
        AiProvider::updateOrCreate(
            ['slug' => 'deepseek'],
            [
                'name' => 'DeepSeek',
                'driver' => 'deepseek',
                'model' => 'deepseek-chat',
                'api_url' => 'https://api.deepseek.com/chat/completions',
                'is_active' => true,
                'is_free' => true,
                'daily_limit' => 100,
            ]
        );

        AiProvider::updateOrCreate(
            ['slug' => 'gemini'],
            [
                'name' => 'Google Gemini',
                'driver' => 'gemini',
                'model' => 'gemini-1.5-flash',
                'api_url' => 'https://generativelanguage.googleapis.com/v1beta/models',
                'is_active' => true,
                'is_free' => true,
                'daily_limit' => 100,
            ]
        );

        AiProvider::updateOrCreate(
            ['slug' => 'openrouter-qwen'],
            [
                'name' => 'OpenRouter Qwen Free',
                'driver' => 'openrouter',
                'model' => 'qwen/qwen-2.5-7b-instruct:free',
                'api_url' => 'https://openrouter.ai/api/v1/chat/completions',
                'is_active' => true,
                'is_free' => true,
                'daily_limit' => 50,
            ]
        );
    }
}