<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchGoal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'match_id',
        'team_id',
        'scorer_id',
        'minute',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'minute' => 'integer',
    ];

    /**
     * Get the match this goal belongs to.
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(TeamMatch::class, 'match_id');
    }

    /**
     * Get the team that scored this goal.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user (player) who scored this goal.
     */
    public function scorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scorer_id');
    }

    /**
     * Format the minute for display.
     */
    public function formatMinute(): string
    {
        return "{$this->minute}'";
    }
}
