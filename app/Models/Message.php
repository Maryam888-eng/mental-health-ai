<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'role',
        'content',
        'is_crisis',
        'meta',
        'sentiment_score',
        'contains_emergency_keyword',
    ];

    protected $casts = [
        'is_crisis' => 'boolean',
        'meta' => 'array',
        'sentiment_score' => 'float',
        'contains_emergency_keyword' => 'boolean',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function aiResponses()
    {
        return $this->hasMany(AiResponse::class);
    }

    public function scopeUserMessages($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeAssistantMessages($query)
    {
        return $query->where('role', 'assistant');
    }

    public function scopeCrisis($query)
    {
        return $query->where('is_crisis', true);
    }

    /**
     * Emergency keywords check
     */
    public function detectEmergencyKeywords(): bool
    {
        $emergencyWords = [
            'suicide', 'kill myself', 'end my life', 'want to die',
            'self-harm', 'harm myself', 'die', 'death',
            'emergency', 'urgent', 'help me', 'danger',
            'overdose', 'bleeding', 'attack', 'unconscious'
        ];

        foreach ($emergencyWords as $word) {
            if (stripos($this->content, $word) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Simple sentiment analysis (positive, negative, neutral)
     */
    public function analyzeSentiment(): string
    {
        $positive = ['good', 'great', 'happy', 'better', 'nice', 'fine', 'well', 'excellent'];
        $negative = ['sad', 'bad', 'terrible', 'awful', 'depressed', 'anxious', 'scared', 'worried'];

        $content = strtolower($this->content);

        $posCount = 0;
        $negCount = 0;

        foreach ($positive as $word) {
            if (strpos($content, $word) !== false) $posCount++;
        }

        foreach ($negative as $word) {
            if (strpos($content, $word) !== false) $negCount++;
        }

        if ($posCount > $negCount) return 'positive';
        if ($negCount > $posCount) return 'negative';
        return 'neutral';
    }

    /**
     * Get message importance level (for summaries)
     */
    public function getImportanceLevel(): string
    {
        if ($this->is_crisis || $this->contains_emergency_keyword) {
            return 'high';
        }

        if (strlen($this->content) > 100) {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Check if message needs immediate attention
     */
    public function needsImmediateAttention(): bool
    {
        return $this->is_crisis || 
               $this->contains_emergency_keyword || 
               $this->analyzeSentiment() === 'negative';
    }

    /**
     * Get short preview (for history)
     */
    public function getPreviewAttribute(): string
    {
        return strlen($this->content) > 50 
            ? substr($this->content, 0, 50) . '...' 
            : $this->content;
    }

    /**
     * Format message with emoji based on sentiment
     */
    public function getEmojiAttribute(): string
    {
        $sentiment = $this->analyzeSentiment();
        return match ($sentiment) {
            'positive' => '😊',
            'negative' => '😔',
            default => '😐',
        };
    }

    /**
     * Check if this is a follow-up message
     */
    public function isFollowUp(): bool
    {
        $previousMessages = $this->conversation
            ->messages()
            ->where('id', '<', $this->id)
            ->where('role', 'user')
            ->count();

        return $previousMessages > 0;
    }

    /**
     * Get response time of AI (if assistant message)
     */
    public function getResponseTime(): ?int
    {
        if ($this->role !== 'assistant') return null;

        $userMessage = $this->conversation
            ->messages()
            ->where('role', 'user')
            ->where('id', '<', $this->id)
            ->latest()
            ->first();

        if (!$userMessage) return null;

        return $this->created_at->diffInSeconds($userMessage->created_at);
    }

    /**
     * 🚨 EMERGENCY ALERT TRIGGER 🚨
     * Check if this message should trigger an emergency alert
     */
    public function shouldTriggerEmergencyAlert(): bool
    {
        return $this->detectEmergencyKeywords() || 
               $this->is_crisis || 
               ($this->analyzeSentiment() === 'negative' && $this->getImportanceLevel() === 'high');
    }

    /**
     * Get emergency alert message
     */
    public function getEmergencyAlertMessage(): string
    {
        $riskLevel = $this->conversation->risk_score ?? 'unknown';
        $userName = $this->conversation->user->name ?? 'Unknown User';
        
        $alertTypes = [
            'crisis' => '🚨 CRISIS ALERT',
            'high' => '⚠️ HIGH RISK',
            'medium' => '🔔 MEDIUM RISK',
            'low' => 'ℹ️ INFO',
        ];

        $type = $alertTypes[$riskLevel] ?? 'ℹ️ INFO';

        return sprintf(
            "[%s] User: %s\nMessage: %s\nConversation ID: %d\nTime: %s",
            $type,
            $userName,
            substr($this->content, 0, 200),
            $this->conversation_id,
            $this->created_at->format('Y-m-d H:i:s')
        );
    }

    /**
     * Get emergency level (for priority sorting)
     */
    public function getEmergencyLevel(): int
    {
        if ($this->detectEmergencyKeywords() || $this->is_crisis) {
            return 1; // Highest priority
        }

        if ($this->analyzeSentiment() === 'negative') {
            return 2; // Medium priority
        }

        return 3; // Low priority
    }

    /**
     * Check if this message contains mental health crisis keywords
     */
    public function containsCrisisKeywords(): bool
    {
        $crisisWords = [
            'suicide', 'kill myself', 'want to die', 'end my life',
            'self harm', 'hurt myself', 'overdose', 'bleeding'
        ];

        foreach ($crisisWords as $word) {
            if (stripos($this->content, $word) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get suggested action based on message content
     */
    public function getSuggestedAction(): string
    {
        if ($this->containsCrisisKeywords()) {
            return '🚨 IMMEDIATE: Call emergency services (911) or crisis helpline (988)';
        }

        if ($this->detectEmergencyKeywords()) {
            return '⚠️ URGENT: Contact a mental health professional or crisis center';
        }

        if ($this->analyzeSentiment() === 'negative') {
            return '💬 Suggest coping strategies or refer to a counselor';
        }

        return '✅ Continue normal conversation';
    }
}