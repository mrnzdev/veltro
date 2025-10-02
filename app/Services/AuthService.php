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
}
