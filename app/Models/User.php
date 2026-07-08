<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'date_of_birth',
        'gender',
        'bio',
        'display_name',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    // ===== ROLE CHECKS =====
    
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ===== RELATIONSHIPS =====
    
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function doctorReviews()
    {
        return $this->hasMany(DoctorReview::class, 'doctor_id');
    }

    public function moodEntries()
    {
        return $this->hasMany(MoodEntry::class);
    }

    public function memories()
    {
        return $this->hasMany(UserMemory::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // ===== SOCIAL FEATURES =====
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function diaries()
    {
        return $this->hasMany(Diary::class);
    }

    public function getDisplayNameAttribute()
    {
        return $this->attributes['display_name'] ?? $this->name;
    }

    public function hasLikedPost($postId)
    {
        return $this->likes()->where('post_id', $postId)->exists();
    }
}