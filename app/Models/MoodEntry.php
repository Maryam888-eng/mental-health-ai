<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodEntry extends Model
{
    protected $fillable = [
        'user_id',
        'mood_score',
        'mood_label',
        'note',
    ];

    protected $casts = [
        'mood_score' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query)
    {
        return $query->latest();
    }
}