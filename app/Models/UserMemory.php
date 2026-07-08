<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMemory extends Model
{
    protected $fillable = [
        'user_id',
        'memory_key',
        'memory_value',
        'confidence',
    ];

    protected $casts = [
        'confidence' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeConfident($query, int $minimum = 70)
    {
        return $query->where('confidence', '>=', $minimum);
    }
}