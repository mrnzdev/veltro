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
     * Football type configurations.
     */
    public const FOOTBALL_TYPES = [
        'football_5' => [
            'name' => 'Fútbol 5',
            'max_members' => 5,
            'icon' => '⚽',
        ],
        'football_7' => [
            'name' => 'Fútbol 7',
            'max_members' => 7,
            'icon' => '⚽',
        ],
        'football_11' => [
            'name' => 'Fútbol 11',
            'max_members' => 11,
            'icon' => '⚽',
        ],
        'futsal' => [
            'name' => 'Futsal',
            'max_members' => 5,
            'icon' => '🏐',
        ],
    ];

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
        'football_type',
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
     * Get the join requests for this team.
     */
    public function joinRequests()
    {
        return $this->hasMany(TeamJoinRequest::class);
    }

    /**
     * Get the pending join requests for this team.
     */
    public function pendingJoinRequests()
    {
        return $this->hasMany(TeamJoinRequest::class)->where('status', 'pending');
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

    /**
     * Get the football type name.
     */
    public function getFootballTypeName(): string
    {
        return self::FOOTBALL_TYPES[$this->football_type]['name'] ?? 'Desconocido';
    }

    /**
     * Get the football type icon.
     */
    public function getFootballTypeIcon(): string
    {
        return self::FOOTBALL_TYPES[$this->football_type]['icon'] ?? '⚽';
    }

    /**
     * Get the max members for a football type.
     */
    public static function getMaxMembersForType(string $footballType): int
    {
        return self::FOOTBALL_TYPES[$footballType]['max_members'] ?? 11;
    }

    /**
     * Boot method to set max_members based on football_type.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Team $team) {
            if ($team->football_type && !$team->max_members) {
                $team->max_members = self::getMaxMembersForType($team->football_type);
            }
        });

        static::updating(function (Team $team) {
            if ($team->isDirty('football_type')) {
                $team->max_members = self::getMaxMembersForType($team->football_type);
            }
        });
    }
}
