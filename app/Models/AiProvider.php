<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiProvider extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'driver',
        'model',
        'api_url',
        'is_active',
        'is_free',
        'daily_limit',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_free' => 'boolean',
        'settings' => 'array',
    ];

    public function doctorReviews()
    {
        return $this->hasMany(\App\Models\DoctorReview::class, 'ai_provider_id');
    }

    public function aiResponses()
    {
        return $this->hasMany(\App\Models\AiResponse::class, 'ai_provider_id');
    }
}