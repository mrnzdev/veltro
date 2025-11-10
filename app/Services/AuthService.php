<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Register a new user using stored procedure
     */
    public function registerUser(string $name, string $email, string $password): array
    {
        $hashedPassword = Hash::make($password);

        $result = DB::select('CALL sp_register_user(?, ?, ?)', [
            $name,
            $email,
            $hashedPassword
        ]);

        return [
            'user_id' => (int) $result[0]->user_id,
            'success' => (bool) $result[0]->success,
            'message' => $result[0]->message
        ];
    }

    /**
     * Check if email is unique using stored procedure
     */
    public function checkEmailUnique(string $email): bool
    {
        $result = DB::select('CALL sp_check_email_unique(?)', [$email]);

        return (bool) $result[0]->is_unique;
    }

    /**
     * Get user by email using stored procedure
     */
    public function getUserByEmail(string $email): ?array
    {
        $result = DB::select('CALL sp_get_user_by_email(?)', [$email]);

        if (!empty($result) && isset($result[0]->user_exists) && $result[0]->user_exists) {
            return [
                'id' => (int) $result[0]->user_id,
                'name' => $result[0]->user_name,
                'email' => $result[0]->user_email,
                'password' => $result[0]->user_password
            ];
        }

        return null;
    }

    /**
     * Authenticate user using stored procedure and Laravel's Hash::check
     */
    public function authenticateUser(string $email, string $password): array
    {
        $user = $this->getUserByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
                'user' => null
            ];
        }

        if (!Hash::check($password, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
                'user' => null
            ];
        }

        return [
            'success' => true,
            'message' => 'Authentication successful',
            'user' => $user
        ];
    }

    /**
     * Find or create OAuth user
     */
    public function findOrCreateOAuthUser(string $googleId, string $email, string $name, ?string $avatar): array
    {
        try {
            DB::beginTransaction();

            // Check if user exists by google_id
            $userByGoogleId = DB::table('users')->where('google_id', $googleId)->first();

            if ($userByGoogleId) {
                // Update avatar if provided
                if ($avatar) {
                    DB::table('users')
                        ->where('id', $userByGoogleId->id)
                        ->update(['avatar' => $avatar, 'updated_at' => now()]);
                }

                DB::commit();

                return [
                    'success' => true,
                    'user_id' => $userByGoogleId->id,
                    'is_new' => false,
                    'linked' => false,
                ];
            }

            // Check if user exists by email
            $userByEmail = DB::table('users')->where('email', $email)->first();

            if ($userByEmail) {
                // Link Google account to existing user
                DB::table('users')
                    ->where('id', $userByEmail->id)
                    ->update([
                        'google_id' => $googleId,
                        'avatar' => $avatar ?? $userByEmail->avatar,
                        'updated_at' => now(),
                    ]);

                DB::commit();

                return [
                    'success' => true,
                    'user_id' => $userByEmail->id,
                    'is_new' => false,
                    'linked' => true,
                ];
            }

            // Create new user with Google OAuth
            $userId = DB::table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'avatar' => $avatar,
                'password' => null,
                'email_verified_at' => now(), // Google accounts are pre-verified
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return [
                'success' => true,
                'user_id' => $userId,
                'is_new' => true,
                'linked' => false,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'user_id' => null,
                'is_new' => false,
                'linked' => false,
            ];
        }
    }
}
