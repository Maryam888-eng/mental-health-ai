<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function ask(AiProvider $provider, array $messages): array
    {
        $started = microtime(true);

        $contents = collect($messages)
            ->whereIn('role', ['user', 'assistant'])
            ->map(function ($message) {
                return [
                    'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [
                        ['text' => $message['content']],
                    ],
                ];
            })
            ->values()
            ->toArray();

        $systemInstruction = collect($messages)
            ->where('role', 'system')
            ->pluck('content')
            ->implode("\n");

        $url = rtrim($provider->api_url, '/')
            . '/' . $provider->model
            . ':generateContent?key=' . config('services.gemini.key');

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
            ],
        ];

        if ($systemInstruction) {
            $payload['systemInstruction'] = [
                'parts' => [
                    ['text' => $systemInstruction],
                ],
            ];
        }

        $response = Http::timeout(90)->post($url, $payload);

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
            'content' => $response->json('candidates.0.content.parts.0.text'),
            'error' => null,
            'time_ms' => $time,
            'raw' => $response->json(),
            'usage' => $response->json('usageMetadata', []),
        ];
    }
}