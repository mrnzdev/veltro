<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchApplication extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'match_request_id',
        'applicant_team_id',
        'message',
        'status',
    ];

    /**
     * Get the match request this application is for.
     */
    public function matchRequest(): BelongsTo
    {
        return $this->belongsTo(MatchRequest::class);
    }

    /**
     * Get the team that made the application.
     */
    public function applicantTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'applicant_team_id');
    }

    /**
     * Check if the application is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the application is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if the application is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Accept the application.
     */
    public function accept(): bool
    {
        return $this->update(['status' => 'accepted']);
    }

    /**
     * Reject the application.
     */
    public function reject(): bool
    {
        return $this->update(['status' => 'rejected']);
    }

    /**
     * Scope to get only pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
