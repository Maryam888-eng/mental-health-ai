<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use Illuminate\Support\Facades\Http;

class DeepSeekService
{
    public function ask(AiProvider $provider, array $messages): array
    {
        $started = microtime(true);

        $response = Http::withToken(config('services.deepseek.key'))
            ->timeout(90)
            ->post($provider->api_url, [
                'model' => $provider->model,
                'messages' => $messages,
                'temperature' => 0.7,
            ]);

        $time = (int) ((microtime(true) - $started) * 1000);

        if ($response->failed()) {
            return [
                'success' => false,
                'content' => null,
                'error' => $response->body(),
                'time_ms' => $time,
                'raw' => $response->json(),
                'usage' => [],
            ];
        }

        return [
            'success' => true,
            'content' => $response->json('choices.0.message.content'),
            'error' => null,
            'time_ms' => $time,
            'raw' => $response->json(),
            'usage' => $response->json('usage', []),
        ];
    }
}