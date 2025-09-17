<?php

declare(strict_types=1);

namespace App\Strategies;

interface AuthStrategyInterface
{
    /**
     * Authenticate user with the given credentials
     */
    public function authenticate(string $email, string $password): array;

    /**
     * Register user with the given data
     */
    public function register(array $userData): array;

    /**
     * Get strategy name
     */
    public function getStrategyName(): string;
}
