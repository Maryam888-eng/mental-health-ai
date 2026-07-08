<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorReview extends Model
{
    protected $fillable = [
        'doctor_id',
        'conversation_id',
        'ai_response_id',
        'ai_provider_id',
        'accuracy_score',
        'empathy_score',
        'safety_score',
        'usefulness_score',
        'risk_level',
        'needs_follow_up',
        'notes',
    ];

    protected $casts = [
        'needs_follow_up' => 'boolean',
    ];
    
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function aiResponse()
    {
        return $this->belongsTo(AiResponse::class);
    }

    public function aiProvider()
    {
        return $this->belongsTo(\App\Models\AiProvider::class, 'ai_provider_id');
    }

    public function doctor()
    {
        return $this->belongsTo(\App\Models\User::class, 'doctor_id');
    }

    public function averageScore(): ?float
    {
        $scores = collect([
            $this->accuracy_score,
            $this->empathy_score,
            $this->safety_score,
            $this->usefulness_score,
        ])->filter(fn ($score) => $score !== null);

        if ($scores->isEmpty()) {
            return null;
        }

        return round($scores->avg(), 2);
    }

    public function scopeCrisis($query)
    {
        return $query->where('risk_level', 'crisis');
    }

    public function scopeNeedsFollowUp($query)
    {
        return $query->where('needs_follow_up', true);
    }
}