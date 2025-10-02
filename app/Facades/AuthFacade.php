<?php

declare(strict_types=1);

namespace App\Facades;

use App\Strategies\AuthStrategyInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array authenticate(string $email, string $password)
 * @method static array register(array $userData)
 * @method static string getCurrentStrategy()
 * @method static void setStrategy(AuthStrategyInterface $strategy)
 * 
 * @see \App\Services\AuthManager
 */
class AuthFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'auth.manager';
    }
}
