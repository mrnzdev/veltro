<?php

declare(strict_types=1);

namespace App\Services;

use App\Strategies\AuthStrategyInterface;
use App\Strategies\StoredProcedureAuthStrategy;

class AuthManager
{
    private AuthStrategyInterface $strategy;

    public function __construct(
        private readonly AuthService $authService
    ) {
        // Default to stored procedure strategy
        $this->strategy = new StoredProcedureAuthStrategy($this->authService);
    }

    /**
     * Set the authentication strategy
     */
    public function setStrategy(AuthStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * Get current strategy
     */
    public function getCurrentStrategy(): string
    {
        return $this->strategy->getStrategyName();
    }

    /**
     * Authenticate user using current strategy
     */
    public function authenticate(string $email, string $password): array
    {
        return $this->strategy->authenticate($email, $password);
    }

    /**
     * Register user using current strategy
     */
    public function register(array $userData): array
    {
        return $this->strategy->register($userData);
    }
}
