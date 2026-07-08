<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiResponse extends Model
{
    protected $fillable = [
        'conversation_id',
        'message_id',
        'ai_provider_id',
        'response',
        'response_time_ms',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'is_successful',
        'error_message',
        'raw_response',
    ];

    protected $casts = [
        'is_successful' => 'boolean',
        'raw_response' => 'array',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function aiProvider()
    {
        return $this->belongsTo(AiProvider::class);
    }

    public function doctorReviews()
    {
        return $this->hasMany(DoctorReview::class);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('is_successful', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('is_successful', false);
    }
}