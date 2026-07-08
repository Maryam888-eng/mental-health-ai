<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'status',
        'is_crisis',
        'summary',
        'risk_score',
        'risk_assessed_at',
    ];

    protected $casts = [
        'is_crisis' => 'boolean',
        'risk_assessed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function aiResponses()
    {
        return $this->hasMany(AiResponse::class);
    }

    public function doctorReviews()
    {
        return $this->hasMany(DoctorReview::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function latestAiResponse()
    {
        return $this->hasOne(AiResponse::class)->latestOfMany();
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeFlagged($query)
    {
        return $query->where('status', 'flagged')
            ->orWhere('is_crisis', true);
    }

    public function scopeByRisk($query, string $level)
    {
        return $query->where('risk_score', $level);
    }

    public function scopeHighRisk($query)
    {
        return $query->whereIn('risk_score', ['high', 'crisis']);
    }

    /**
     * AI Health Risk Score - Chat ke basis par risk assess karein
     */
    public function assessRisk(): string
    {
        $keywords = [
            'crisis' => [
                'suicide', 'kill myself', 'end my life', 'die', 
                'self-harm', 'harm myself', 'want to die'
            ],
            'high' => [
                'panic', 'severe', 'desperate', 'hopeless', 
                'overdose', 'bleeding', 'can\'t go on'
            ],
            'medium' => [
                'anxiety', 'depressed', 'crying', 'scared', 
                'worried', 'stress', 'overwhelmed'
            ],
            'low' => [
                'sad', 'tired', 'okay', 'fine', 'good', 
                'normal', 'feeling better'
            ]
        ];

        $messages = $this->messages()->pluck('content')->implode(' ');

        foreach ($keywords as $level => $words) {
            foreach ($words as $word) {
                if (stripos($messages, $word) !== false) {
                    return $level;
                }
            }
        }

        return 'low';
    }

    /**
     * Risk score ka color (UI ke liye)
     */
    public function getRiskColorAttribute(): string
    {
        return match ($this->risk_score) {
            'crisis' => '#dc3545', // red
            'high'   => '#fd7e14', // orange
            'medium' => '#ffc107', // yellow
            default  => '#28a745', // green
        };
    }

    /**
     * Risk score ka label
     */
    public function getRiskLabelAttribute(): string
    {
        return match ($this->risk_score) {
            'crisis' => '⚠️ Crisis',
            'high'   => '🔴 High Risk',
            'medium' => '🟡 Medium Risk',
            default  => '🟢 Low Risk',
        };
    }

    /**
     * Check if conversation needs emergency alert
     */
    public function needsEmergencyAlert(): bool
    {
        return in_array($this->risk_score, ['high', 'crisis']);
    }

    /**
     * Check if conversation needs doctor review
     */
    public function needsDoctorReview(): bool
    {
        return in_array($this->risk_score, ['medium', 'high', 'crisis']);
    }

    /**
     * Get summary for doctor report
     */
    public function generateMedicalSummary(): string
    {
        $messages = $this->messages()->take(20)->get();
        $summary = "Conversation #{$this->id}\n";
        $summary .= "User: {$this->user->name}\n";
        $summary .= "Risk Level: {$this->risk_score}\n";
        $summary .= "Date: {$this->created_at->format('Y-m-d H:i')}\n\n";
        $summary .= "--- Key Points ---\n";

        foreach ($messages as $msg) {
            $summary .= $msg->role . ": " . substr($msg->content, 0, 100) . "...\n";
        }

        return $summary;
    }

    /**
     * Check if follow-up is needed
     */
    public function needsFollowUp(): bool
    {
        $lastMessage = $this->latestMessage;
        if (!$lastMessage) return false;

        // Agar 3 din se zyada ho gaye to follow-up chahiye
        $daysSinceLast = $lastMessage->created_at->diffInDays(now());
        
        return $daysSinceLast >= 3 || $this->needsEmergencyAlert();
    }
}