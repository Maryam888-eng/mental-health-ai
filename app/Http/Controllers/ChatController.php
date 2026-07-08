<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\EmergencyAlert;
use App\Services\AI\AIManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // 🔴 Import Log
use Illuminate\Support\Carbon; // 🔴 Import Carbon

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index', [
            'conversations' => Conversation::query()
                ->where('user_id', Auth::id())
                ->latest()
                ->get(),
        ]);
    }

    public function start()
    {
        $conversation = Conversation::create([
            'user_id' => Auth::id(),
            'title' => 'New Conversation',
        ]);

        return response()->json($conversation);
    }

    public function show(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $messages = $conversation->messages()
            ->with(['aiResponses.aiProvider'])
            ->oldest()
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'role' => $message->role,
                    'content' => $message->content,
                    'created_at' => $message->created_at,
                    'ai_responses' => $message->aiResponses,
                ];
            });
        
        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    public function send(Request $request, Conversation $conversation, AIManager $manager)
    {
        $this->authorizeConversation($conversation);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $content = trim($data['message']);
        $isCrisis = $this->isCrisis($content);

        // Save user message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $content,
            'is_crisis' => $isCrisis,
            'contains_emergency_keyword' => $isCrisis,
        ]);

        // Update conversation title if new
        if ($conversation->title === 'New Conversation') {
            $conversation->update([
                'title' => str($content)->limit(45),
            ]);
        }

        // 🚨 EMERGENCY ALERT CHECK
        if ($isCrisis) {
            // Update conversation
            $conversation->update([
                'is_crisis' => true,
                'status' => 'flagged',
                'risk_score' => 'crisis',
                'risk_assessed_at' => Carbon::now(), // 🔴 Fixed
            ]);

            // 🔴 CREATE EMERGENCY ALERT
            try {
                EmergencyAlert::create([
                    'user_id' => Auth::id(),
                    'conversation_id' => $conversation->id,
                    'message_id' => $message->id,
                    'alert_type' => 'crisis',
                    'priority_level' => 1,
                    'message' => '🚨 CRISIS ALERT: User said: "' . substr($content, 0, 200) . '"',
                    'is_resolved' => false,
                    'trigger_keyword' => $this->getTriggerKeyword($content),
                    'meta_data' => [
                        'user_name' => Auth::user()->name,
                        'user_email' => Auth::user()->email,
                        'detected_at' => Carbon::now()->toDateTimeString(), // 🔴 Fixed
                    ],
                ]);
            } catch (\Exception $e) {
                // Log error but continue
                Log::error('Emergency Alert creation failed: ' . $e->getMessage()); // 🔴 Fixed
            }

            // Crisis response
            Message::create([
                'conversation_id' => $conversation->id,
                'role' => 'assistant',
                'content' => $this->crisisMessage(),
                'is_crisis' => true,
                'meta' => ['type' => 'safety'],
            ]);

            return response()->json([
                'crisis' => true,
                'message' => $this->crisisMessage(),
                'responses' => [],
            ]);
        }

        // If not crisis, get AI responses
        $responses = $manager->askAllProviders($conversation, $message);

        // Update risk score after AI response
        $conversation->risk_score = $conversation->assessRisk();
        $conversation->risk_assessed_at = Carbon::now(); // 🔴 Fixed
        $conversation->save();

        return response()->json([
            'crisis' => false,
            'message' => $message,
            'responses' => $responses,
        ]);
    }

    public function destroy(Conversation $conversation)
    {
        $this->authorizeConversation($conversation);

        $conversation->delete();

        return response()->json(['success' => true]);
    }

    private function authorizeConversation(Conversation $conversation): void
    {
        abort_unless(
            Auth::check() && (int) $conversation->user_id === Auth::id(),
            403
        );
    }

    /**
     * Check if message contains crisis keywords
     */
    private function isCrisis(string $text): bool
    {
        $text = strtolower($text);

        $terms = [
            'kill myself',
            'suicide',
            'end my life',
            'want to die',
            'hurt myself',
            'self harm',
            'harm myself',
            'kill someone',
            'hurt someone',
            'take my life',
            'i want to die',
            'i\'m going to kill myself',
        ];

        foreach ($terms as $term) {
            if (str_contains($text, $term)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the keyword that triggered the crisis alert
     */
    private function getTriggerKeyword(string $text): string
    {
        $text = strtolower($text);
        $terms = [
            'kill myself', 'suicide', 'end my life', 'want to die',
            'hurt myself', 'self harm', 'harm myself', 'take my life'
        ];

        foreach ($terms as $term) {
            if (str_contains($text, $term)) {
                return $term;
            }
        }

        return 'unknown';
    }

    /**
     * Crisis response message
     */
    private function crisisMessage(): string
    {
        return 'I\'m really sorry you are feeling this way. This chat is not an emergency service. '
            . 'If you may harm yourself or someone else, please contact emergency services immediately. '
            . 'If you are in the U.S. or Canada, call or text 988. If you are elsewhere, contact your local emergency number or a trusted person nearby now. '
            . 'A crisis alert has been sent to our support team. You are not alone. Please stay safe. 🫂';
    }
}