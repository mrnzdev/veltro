<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Services\AuthService;

class StoredProcedureAuthStrategy implements AuthStrategyInterface
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    /**
     * Authenticate user using stored procedures
     */
    public function authenticate(string $email, string $password): array
    {
        return $this->authService->authenticateUser($email, $password);
    }

    /**
     * Register user using stored procedures
     */
    public function register(array $userData): array
    {
        return $this->authService->registerUser(
            $userData['name'],
            $userData['email'],
            $userData['password']
        );
    }

    /**
     * Get strategy name
     */
    public function getStrategyName(): string
    {
        return 'stored_procedure';
    }
}
