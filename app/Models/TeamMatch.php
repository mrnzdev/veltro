<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamMatch extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'matches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'match_request_id',
        'team_id',
        'opponent_team_id',
        'match_datetime',
        'location',
        'status',
        'team_result_submitted_at',
        'team_result_submitted_by',
        'opponent_result_submitted_at',
        'opponent_result_submitted_by',
        'result_confirmed_at',
        'team_score',
        'opponent_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'match_datetime' => 'datetime',
        'team_result_submitted_at' => 'datetime',
        'opponent_result_submitted_at' => 'datetime',
        'result_confirmed_at' => 'datetime',
        'team_score' => 'integer',
        'opponent_score' => 'integer',
    ];

    /**
     * Get the match request that created this match.
     */
    public function matchRequest(): BelongsTo
    {
        return $this->belongsTo(MatchRequest::class);
    }

    /**
     * Get the team that requested the match.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Get the opponent team.
     */
    public function opponentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'opponent_team_id');
    }

    /**
     * Check if the match is scheduled.
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Check if the match is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the match is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Cancel the match.
     */
    public function cancel(): bool
    {
        if ($this->status === 'scheduled') {
            return $this->update(['status' => 'cancelled']);
        }

        return false;
    }

    /**
     * Mark the match as completed.
     */
    public function markCompleted(): bool
    {
        if ($this->status === 'scheduled') {
            return $this->update(['status' => 'completed']);
        }

        return false;
    }

    /**
     * Scope to get only scheduled matches.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope to get upcoming matches.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
            ->where('match_datetime', '>', now());
    }

    /**
     * Get the participants in this match.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(MatchParticipant::class, 'match_id');
    }

    /**
     * Get the goals scored in this match.
     */
    public function goals(): HasMany
    {
        return $this->hasMany(MatchGoal::class, 'match_id');
    }

    /**
     * Get the disputes for this match.
     */
    public function disputes(): HasMany
    {
        return $this->hasMany(MatchDispute::class, 'match_id');
    }

    /**
     * Get the user who submitted results for the requesting team.
     */
    public function teamResultSubmitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'team_result_submitted_by');
    }

    /**
     * Get the user who submitted results for the opponent team.
     */
    public function opponentResultSubmitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opponent_result_submitted_by');
    }

    /**
     * Check if a user can view results for this match.
     * More permissive than canRecordResults - allows viewing completed matches.
     */
    public function canViewResults(User $user): bool
    {
        $requestingTeam = $this->team;
        $opponentTeam = $this->opponentTeam;

        $isRequestingTeamMember = $requestingTeam->isOwnedBy($user) || $requestingTeam->isCaptain($user);
        $isOpponentTeamMember = $opponentTeam->isOwnedBy($user) || $opponentTeam->isCaptain($user);

        return $isRequestingTeamMember || $isOpponentTeamMember;
    }

    /**
     * Check if a user can record results for this match.
     */
    public function canRecordResults(User $user): bool
    {
        if ($this->status !== 'scheduled') {
            return false;
        }

        if ($this->areResultsConfirmed()) {
            return false;
        }

        return $this->canViewResults($user);
    }

    /**
     * Check if the requesting team has submitted results.
     */
    public function hasTeamSubmittedResults(): bool
    {
        return $this->team_result_submitted_at !== null;
    }

    /**
     * Check if the opponent team has submitted results.
     */
    public function hasOpponentSubmittedResults(): bool
    {
        return $this->opponent_result_submitted_at !== null;
    }

    /**
     * Check if results are confirmed by both teams.
     */
    public function areResultsConfirmed(): bool
    {
        return $this->result_confirmed_at !== null;
    }

    /**
     * Calculate scores from goals.
     */
    public function calculateScores(): array
    {
        $teamGoals = $this->goals()->where('team_id', $this->team_id)->count();
        $opponentGoals = $this->goals()->where('team_id', $this->opponent_team_id)->count();

        return [
            'team_score' => $teamGoals,
            'opponent_score' => $opponentGoals,
        ];
    }

    /**
     * Get goals for the requesting team.
     */
    public function getTeamGoals()
    {
        return $this->goals()->where('team_id', $this->team_id)->with('scorer')->orderBy('minute')->get();
    }

    /**
     * Get goals for the opponent team.
     */
    public function getOpponentGoals()
    {
        return $this->goals()->where('team_id', $this->opponent_team_id)->with('scorer')->orderBy('minute')->get();
    }

    /**
     * Get which team the user belongs to in this match.
     * Returns 'team', 'opponent', or null.
     */
    public function getUserTeamSide(User $user): ?string
    {
        if ($this->team->isOwnedBy($user) || $this->team->isCaptain($user)) {
            return 'team';
        }

        if ($this->opponentTeam->isOwnedBy($user) || $this->opponentTeam->isCaptain($user)) {
            return 'opponent';
        }

        return null;
    }
}
