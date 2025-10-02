<?php

declare(strict_types=1);

namespace App\Factories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory
{
    /**
     * Create a regular user
     */
    public static function createRegularUser(array $attributes = []): User
    {
        return self::createUser(array_merge([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
        ], $attributes));
    }

    /**
     * Create a team owner user
     */
    public static function createTeamOwner(array $attributes = []): User
    {
        return self::createUser(array_merge([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
        ], $attributes));
    }

    /**
     * Create a captain user
     */
    public static function createCaptain(array $attributes = []): User
    {
        return self::createUser(array_merge([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
        ], $attributes));
    }

    /**
     * Create a regular member user
     */
    public static function createMember(array $attributes = []): User
    {
        return self::createUser(array_merge([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
        ], $attributes));
    }

    /**
     * Create multiple users
     */
    public static function createMultiple(int $count, string $type = 'regular'): array
    {
        $users = [];
        
        for ($i = 0; $i < $count; $i++) {
            $users[] = match ($type) {
                'owner' => self::createTeamOwner(),
                'captain' => self::createCaptain(),
                'member' => self::createMember(),
                default => self::createRegularUser(),
            };
        }
        
        return $users;
    }

    /**
     * Create user with custom attributes
     */
    private static function createUser(array $attributes): User
    {
        return User::create($attributes);
    }
}
