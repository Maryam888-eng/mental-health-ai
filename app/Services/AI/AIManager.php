<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use App\Models\AiResponse;
use App\Models\Conversation;
use App\Models\Message;
use Throwable;

class AIManager
{
    public function __construct(
        private DeepSeekService $deepSeek,
        private GeminiService $gemini,
        private OpenRouterService $openRouter,
    ) {}

    public function askAllProviders(Conversation $conversation, Message $message): array
    {
        $providers = AiProvider::where('is_active', true)->get();
        $messages = $this->buildPrompt($conversation);

        $results = [];

        foreach ($providers as $provider) {
            try {
                $result = $this->askProvider($provider, $messages);
            } catch (Throwable $e) {
                $result = [
                    'success' => false,
                    'content' => null,
                    'error' => $e->getMessage(),
                    'time_ms' => null,
                    'raw' => null,
                    'usage' => [],
                ];
            }

            $aiResponse = AiResponse::create([
                'conversation_id' => $conversation->id,
                'message_id' => $message->id,
                'ai_provider_id' => $provider->id,
                'response' => $result['content'] ?: 'No response generated.',
                'response_time_ms' => $result['time_ms'],
                'prompt_tokens' => $result['usage']['prompt_tokens'] ?? null,
                'completion_tokens' => $result['usage']['completion_tokens'] ?? null,
                'total_tokens' => $result['usage']['total_tokens'] ?? null,
                'is_successful' => $result['success'],
                'error_message' => $result['error'],
                'raw_response' => $result['raw'],
            ]);

            $results[] = $aiResponse->load('aiProvider');
        }

        return $results;
    }

    private function mockResponse(array $messages): array
    {
        $lastUserMessage = collect($messages)
            ->where('role', 'user')
            ->last()['content'] ?? '';

        return [
            'success' => true,
            'content' => "I hear you. From an academic mental-wellness perspective, it may help to pause, name the feeling, and choose one small next step. You mentioned: \"{$lastUserMessage}\". This is not medical advice, but journaling, breathing exercises, rest, and speaking with a trusted person may help.",
            'error' => null,
            'time_ms' => 100,
            'raw' => ['provider' => 'mock'],
            'usage' => [],
        ];
    }

    private function askProvider(AiProvider $provider, array $messages): array
    {
        return match ($provider->driver) {
            'deepseek' => $this->deepSeek->ask($provider, $messages),
            'gemini' => $this->gemini->ask($provider, $messages),
            'openrouter' => $this->openRouter->ask($provider, $messages),
            'mock' => $this->mockResponse($messages),
            default => [
                'success' => false,
                'content' => null,
                'error' => 'Unsupported provider.',
                'time_ms' => null,
                'raw' => null,
                'usage' => [],
            ],
        };
    }

    // private function buildPrompt(Conversation $conversation): array
    // {
    //     $useEnhancedPrompt = true;

    //     $system = <<<PROMPT
    // You are a friendly mental wellness chat assistant for an academic project.

    // Important:
    // - Reply based mainly on the user's latest message.
    // - Continue the conversation naturally.
    // - Do not repeat greetings if the user already answered.
    // - If the user says they are good, happy, fine, or okay, respond positively.
    // - Do not assume sadness or anxiety unless the user says it.
    // - Keep replies short and natural.
    // - Ask at most one simple follow-up question.
    // - Do not diagnose.
    // - Do not prescribe medication.
    // - Do not act like a doctor or therapist.
    // - If there is self-harm, suicide, violence, or immediate danger, advise emergency support immediately.
    // PROMPT;

    //     $prompt = [];

    //     if ($useEnhancedPrompt) {
    //         $prompt[] = [
    //             'role' => 'system',
    //             'content' => $system,
    //         ];
    //     }

    //     $messages = $conversation->messages()
    //         ->with([
    //             'aiResponses' => function ($query) {
    //                 $query->where('is_successful', true)
    //                     ->oldest();
    //             }
    //         ])
    //         ->where('role', 'user')
    //         ->latest()
    //         ->limit($useEnhancedPrompt ? 6 : 1)
    //         ->get()
    //         ->reverse()
    //         ->values();

    //     foreach ($messages as $message) {

    //         $prompt[] = [
    //             'role' => 'user',
    //             'content' => $message->content,
    //         ];

    //         if ($useEnhancedPrompt) {
    //             foreach ($message->aiResponses as $response) {
    //                 $prompt[] = [
    //                     'role' => 'assistant',
    //                     'content' => $response->response,
    //                 ];
    //             }
    //         }
    //     }

    //     return $prompt;
    // }
    
    private function buildPrompt(Conversation $conversation): array
    {
        $useEnhancedPrompt = true;

        $system = <<<PROMPT
    You are a friendly mental wellness chat assistant for an academic project.

    Important:
    - Reply based mainly on the user's latest message.
    - Continue the conversation naturally.
    - Do not repeat greetings if the user already answered.
    - If the user says they are good, happy, fine, or okay, respond positively.
    - Do not assume sadness or anxiety unless the user says it.
    - Keep replies short and natural.
    - Ask at most one simple follow-up question.
    - Do not diagnose.
    - Do not prescribe medication.
    - Do not act like a doctor or therapist.
    - If there is self-harm, suicide, violence, or immediate danger, advise emergency support immediately.
    PROMPT;

        $prompt = [];

        if ($useEnhancedPrompt) {
            $prompt[] = [
                'role' => 'system',
                'content' => $system,
            ];
        }

        $messages = $conversation->messages()
            ->where('role', 'user')
            ->latest()
            ->limit($useEnhancedPrompt ? 3 : 1)
            ->get()
            ->reverse()
            ->values();

        foreach ($messages as $message) {

            $prompt[] = [
                'role' => 'user',
                'content' => $message->content,
            ];

            if (!$useEnhancedPrompt) {
                continue;
            }

            $response = $message->aiResponses()
                ->where('is_successful', true)
                ->oldest()
                ->first();

            if (!$response) {
                continue;
            }

            $prompt[] = [
                'role' => 'assistant',
                'content' => mb_substr(
                    strip_tags($response->response ?? ''),
                    0,
                    1000
                ),
            ];
        }

        return $prompt;
    }
}