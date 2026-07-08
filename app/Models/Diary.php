<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'mood',
        'is_shared_with_doctor',
    ];

    protected $casts = [
        'is_shared_with_doctor' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}