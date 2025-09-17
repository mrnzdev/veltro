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

        $result = DB::select('CALL sp_register_user(?, ?, ?, @user_id, @success, @message)', [
            $name,
            $email,
            $hashedPassword
        ]);

        $output = DB::select('SELECT @user_id as user_id, @success as success, @message as message');

        return [
            'user_id' => (int) $output[0]->user_id,
            'success' => (bool) $output[0]->success,
            'message' => $output[0]->message
        ];
    }

    /**
     * Check if email is unique using stored procedure
     */
    public function checkEmailUnique(string $email): bool
    {
        $result = DB::select('CALL sp_check_email_unique(?, @is_unique)', [$email]);

        $output = DB::select('SELECT @is_unique as is_unique');

        return (bool) $output[0]->is_unique;
    }

    /**
     * Get user by email using stored procedure
     */
    public function getUserByEmail(string $email): ?array
    {
        $result = DB::select('CALL sp_get_user_by_email(?, @user_id, @user_name, @user_email, @user_password, @user_exists)', [$email]);

        $output = DB::select('SELECT @user_id as user_id, @user_name as user_name, @user_email as user_email, @user_password as user_password, @user_exists as user_exists');

        if ($output[0]->user_exists) {
            return [
                'id' => (int) $output[0]->user_id,
                'name' => $output[0]->user_name,
                'email' => $output[0]->user_email,
                'password' => $output[0]->user_password
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
