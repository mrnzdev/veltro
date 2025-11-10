<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchDispute extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'match_id',
        'disputed_by',
        'dispute_reason',
        'team_side',
    ];

    /**
     * Get the match that was disputed.
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(TeamMatch::class, 'match_id');
    }

    /**
     * Get the user who disputed the match.
     */
    public function disputedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disputed_by');
    }
}
