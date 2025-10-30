<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MatchRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_id',
        'match_datetime',
        'location',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'match_datetime' => 'datetime',
    ];

    /**
     * Get the team that created the match request.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user who created the match request.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the applications for this match request.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(MatchApplication::class);
    }

    /**
     * Get the pending applications for this match request.
     */
    public function pendingApplications(): HasMany
    {
        return $this->hasMany(MatchApplication::class)->where('status', 'pending');
    }

    /**
     * Get the match created from this request.
     */
    public function match(): HasOne
    {
        return $this->hasOne(TeamMatch::class);
    }

    /**
     * Check if the match request can accept applications.
     */
    public function canAcceptApplications(): bool
    {
        return $this->status === 'open' && $this->match_datetime > now();
    }

    /**
     * Mark the match request as expired.
     */
    public function expire(): bool
    {
        if ($this->status === 'open') {
            return $this->update(['status' => 'expired']);
        }

        return false;
    }

    /**
     * Cancel the match request.
     */
    public function cancel(): bool
    {
        if ($this->status === 'open') {
            return $this->update(['status' => 'cancelled']);
        }

        return false;
    }

    /**
     * Scope to get only open match requests.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope to filter by football type.
     */
    public function scopeForFootballType($query, string $footballType)
    {
        return $query->whereHas('team', function ($q) use ($footballType) {
            $q->where('football_type', $footballType);
        });
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate = null)
    {
        $query->where('match_datetime', '>=', $startDate);

        if ($endDate) {
            $query->where('match_datetime', '<=', $endDate);
        }

        return $query;
    }

    /**
     * Scope to filter by location.
     */
    public function scopeInLocation($query, string $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }
}
