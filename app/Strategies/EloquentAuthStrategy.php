<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EloquentAuthStrategy implements AuthStrategyInterface
{
    /**
     * Authenticate user using Eloquent ORM
     */
    public function authenticate(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();
        
        if (!$user || !Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
                'user' => null
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Authentication successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password
            ]
        ];
    }

    /**
     * Register user using Eloquent ORM
     */
    public function register(array $userData): array
    {
        try {
            // Check if email already exists
            if (User::where('email', $userData['email'])->exists()) {
                return [
                    'user_id' => 0,
                    'success' => false,
                    'message' => 'Email already exists'
                ];
            }

            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);

            return [
                'user_id' => $user->id,
                'success' => true,
                'message' => 'User registered successfully'
            ];
        } catch (\Exception $e) {
            return [
                'user_id' => 0,
                'success' => false,
                'message' => 'Error occurred during registration: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get strategy name
     */
    public function getStrategyName(): string
    {
        return 'eloquent';
    }
}
