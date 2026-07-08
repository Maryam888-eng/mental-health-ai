<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyAlert extends Model
{
    protected $fillable = [
        'user_id',
        'conversation_id',
        'message_id',
        'alert_type',
        'message',
        'is_resolved',
        'resolved_at',
        'resolution_notes',
        'notified_doctor_id',
        'notified_at',
        'priority_level',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
        'notified_at' => 'datetime',
        'priority_level' => 'integer',
    ];

    // ========== Relationships ==========

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function notifiedDoctor()
    {
        return $this->belongsTo(User::class, 'notified_doctor_id');
    }

    // ========== Scopes ==========

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeCrisis($query)
    {
        return $query->where('alert_type', 'crisis');
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority_level', '<=', 2);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    // ========== Main Methods ==========

    /**
     * Resolve the alert
     */
    public function resolve(string $notes = null)
    {
        $this->is_resolved = true;
        $this->resolved_at = now();
        $this->resolution_notes = $notes;
        $this->save();
    }

    /**
     * Mark alert as notified to doctor
     */
    public function markNotified(int $doctorId)
    {
        $this->notified_doctor_id = $doctorId;
        $this->notified_at = now();
        $this->save();
    }

    /**
     * Check if alert is still active (not resolved)
     */
    public function isActive(): bool
    {
        return !$this->is_resolved;
    }

    /**
     * Get time elapsed since alert created
     */
    public function getElapsedTime(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get time to resolve (if resolved)
     */
    public function getResolutionTime(): ?string
    {
        if (!$this->resolved_at) {
            return null;
        }
        return $this->created_at->diffInMinutes($this->resolved_at) . ' minutes';
    }

    // ========== Attributes ==========

    public function getAlertColorAttribute(): string
    {
        return match ($this->alert_type) {
            'crisis' => '#dc3545',
            'high_risk' => '#fd7e14',
            'self_harm' => '#dc3545',
            'medium_risk' => '#ffc107',
            default => '#6c757d',
        };
    }

    public function getAlertIconAttribute(): string
    {
        return match ($this->alert_type) {
            'crisis' => '🚨',
            'high_risk' => '⚠️',
            'self_harm' => '🆘',
            'medium_risk' => '🔔',
            default => 'ℹ️',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority_level) {
            1 => '🚨 Highest (Crisis)',
            2 => '⚠️ High',
            3 => '🔔 Medium',
            4 => 'ℹ️ Low',
            default => 'Unknown',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->is_resolved) {
            return '<span class="badge bg-success">✅ Resolved</span>';
        }
        if ($this->notified_at) {
            return '<span class="badge bg-warning text-dark">📨 Notified</span>';
        }
        return '<span class="badge bg-danger">⚠️ Pending</span>';
    }

    public function getTimeSinceAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get formatted alert summary
     */
    public function getSummaryAttribute(): string
    {
        return sprintf(
            '%s Alert #%d: %s - %s',
            $this->alert_icon,
            $this->id,
            $this->alert_type,
            $this->user->name ?? 'Unknown User'
        );
    }

    // ========== Statistics ==========

    /**
     * Get total alerts count
     */
    public static function getStats(): array
    {
        return [
            'total' => self::count(),
            'pending' => self::unresolved()->count(),
            'resolved' => self::resolved()->count(),
            'crisis' => self::crisis()->count(),
            'today' => self::today()->count(),
            'this_week' => self::thisWeek()->count(),
        ];
    }

    /**
     * Get alert by priority (for dashboard)
     */
    public static function getByPriority(): array
    {
        return [
            'crisis' => self::crisis()->unresolved()->count(),
            'high' => self::where('alert_type', 'high_risk')->unresolved()->count(),
            'medium' => self::where('alert_type', 'medium_risk')->unresolved()->count(),
            'low' => self::where('alert_type', 'low_risk')->unresolved()->count(),
        ];
    }

    // ========== Helper Methods ==========

    /**
     * Check if this is a crisis alert
     */
    public function isCrisis(): bool
    {
        return $this->alert_type === 'crisis';
    }

    /**
     * Check if this alert was notified
     */
    public function isNotified(): bool
    {
        return !is_null($this->notified_at);
    }

    /**
     * Get recommended action based on alert type
     */
    public function getRecommendedAction(): string
    {
        return match ($this->alert_type) {
            'crisis' => '🚨 IMMEDIATE: Call emergency services (911) or crisis helpline (988)',
            'high_risk' => '⚠️ URGENT: Contact a mental health professional within 1 hour',
            'self_harm' => '🆘 CRITICAL: Immediate intervention required',
            'medium_risk' => '📞 Schedule a doctor appointment within 24 hours',
            default => '📋 Monitor and follow up within 48 hours',
        };
    }

    /**
     * Get emergency contact based on location
     */
    public function getEmergencyContact(): string
    {
        // Default emergency contacts (ap ke hisaab se change kar sakte ho)
        $contacts = [
            'US' => '911 (Emergency) | 988 (Suicide Hotline)',
            'UK' => '999 (Emergency) | 116 123 (Samaritans)',
            'PK' => '1122 (Emergency) | 1234 (Mental Health Helpline)',
            'default' => '112 (Emergency) | Contact local crisis center',
        ];

        return $contacts['default'];
    }

    /**
     * Check if alert needs immediate action
     */
    public function needsImmediateAction(): bool
    {
        return in_array($this->alert_type, ['crisis', 'self_harm', 'high_risk']);
    }

    /**
     * Get alert age in minutes
     */
    public function getAgeInMinutes(): int
    {
        return $this->created_at->diffInMinutes(now());
    }

    /**
     * Check if alert is stale (more than 1 hour old)
     */
    public function isStale(): bool
    {
        return $this->getAgeInMinutes() > 60 && !$this->is_resolved;
    }
}