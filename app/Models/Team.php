<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'logo',
        'owner_id',
        'max_members',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'max_members' => 'integer',
    ];

    /**
     * Get the owner of the team.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the members of the team.
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->using(TeamUser::class)
            ->withPivot(['role', 'joined_at'])
            ->withTimestamps();
    }

    /**
     * Get the captains of the team.
     */
    public function captains(): BelongsToMany
    {
        return $this->members()->wherePivot('role', 'captain');
    }

    /**
     * Check if a user is a member of the team.
     */
    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if a user is the owner of the team.
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    /**
     * Check if a user is a captain of the team.
     */
    public function isCaptain(User $user): bool
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'captain')
            ->exists();
    }

    /**
     * Get the current number of members.
     */
    public function getCurrentMembersCount(): int
    {
        return $this->members()->count();
    }

    /**
     * Check if the team has space for more members.
     */
    public function hasSpaceForMembers(): bool
    {
        return $this->getCurrentMembersCount() < $this->max_members;
    }

    /**
     * Scope to get only active teams.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
