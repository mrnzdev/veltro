<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\AuthService;
use App\Services\LoggerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login page can be displayed
     */
    public function test_login_page_can_be_displayed(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * Test users can login with valid credentials
     */
    public function test_users_can_login_with_valid_credentials(): void
    {
        // Create user in database
        $user = User::factory()->create([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Mock AuthService
        $authServiceMock = $this->mock(AuthService::class);
        $authServiceMock->shouldReceive('authenticateUser')
            ->once()
            ->with('test@example.com', 'password')
            ->andReturn([
                'success' => true,
                'message' => 'Authentication successful',
                'user' => [
                    'id' => 1,
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
                ]
            ]);

        // Mock LoggerService
        $loggerServiceMock = $this->mock(LoggerService::class);
        $loggerServiceMock->shouldReceive('logAuthEvent')
            ->once()
            ->with('user_login', \Mockery::type('array'));

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test users cannot login with invalid credentials
     */
    public function test_users_cannot_login_with_invalid_credentials(): void
    {
        // Mock AuthService to return authentication failure
        $authServiceMock = $this->mock(AuthService::class);
        $authServiceMock->shouldReceive('authenticateUser')
            ->once()
            ->with('wrong@example.com', 'wrongpassword')
            ->andReturn([
                'success' => false,
                'message' => 'Invalid credentials',
                'user' => null
            ]);

        // Mock LoggerService
        $loggerServiceMock = $this->mock(LoggerService::class);
        $loggerServiceMock->shouldReceive('logAuthEvent')
            ->once()
            ->with('login_failed', \Mockery::type('array'));

        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.']);
        $this->assertGuest();
    }

    /**
     * Test login fails when email is missing
     */
    public function test_login_fails_when_email_is_missing(): void
    {
        $response = $this->post('/login', [
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test login fails when password is missing
     */
    public function test_login_fails_when_password_is_missing(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    /**
     * Test login fails with invalid email format
     */
    public function test_login_fails_with_invalid_email_format(): void
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test users with wrong password cannot login
     */
    public function test_users_with_wrong_password_cannot_login(): void
    {
        // Create user in database
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // Mock AuthService to return authentication failure
        $authServiceMock = $this->mock(AuthService::class);
        $authServiceMock->shouldReceive('authenticateUser')
            ->once()
            ->with('test@example.com', 'wrongpassword')
            ->andReturn([
                'success' => false,
                'message' => 'Invalid credentials',
                'user' => null
            ]);

        // Mock LoggerService
        $loggerServiceMock = $this->mock(LoggerService::class);
        $loggerServiceMock->shouldReceive('logAuthEvent')
            ->once()
            ->with('login_failed', \Mockery::type('array'));

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test session is regenerated on successful login
     */
    public function test_session_is_regenerated_on_successful_login(): void
    {
        // Create user in database
        $user = User::factory()->create([
            'id' => 1,
            'email' => 'test@example.com',
        ]);

        // Mock AuthService
        $authServiceMock = $this->mock(AuthService::class);
        $authServiceMock->shouldReceive('authenticateUser')
            ->once()
            ->andReturn([
                'success' => true,
                'message' => 'Authentication successful',
                'user' => [
                    'id' => 1,
                    'name' => $user->name,
                    'email' => 'test@example.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
                ]
            ]);

        // Mock LoggerService
        $loggerServiceMock = $this->mock(LoggerService::class);
        $loggerServiceMock->shouldReceive('logAuthEvent')
            ->once();

        // Start a session to track regeneration
        $this->startSession();
        $oldSessionId = session()->getId();

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        
        // Session ID should have changed
        $this->assertNotEquals($oldSessionId, session()->getId());
    }

    /**
     * Test users can be redirected to intended page after login
     */
    public function test_users_can_be_redirected_to_intended_page_after_login(): void
    {
        // Create user in database
        $user = User::factory()->create([
            'id' => 1,
            'email' => 'test@example.com',
        ]);

        // Mock AuthService
        $authServiceMock = $this->mock(AuthService::class);
        $authServiceMock->shouldReceive('authenticateUser')
            ->once()
            ->andReturn([
                'success' => true,
                'message' => 'Authentication successful',
                'user' => [
                    'id' => 1,
                    'name' => $user->name,
                    'email' => 'test@example.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
                ]
            ]);

        // Mock LoggerService
        $loggerServiceMock = $this->mock(LoggerService::class);
        $loggerServiceMock->shouldReceive('logAuthEvent')
            ->once();

        // Simulate trying to access a protected route
        $this->get('/teams');

        // Then login
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Should redirect to the intended page (teams)
        $response->assertRedirect('/teams');
        $this->assertAuthenticated();
    }

    /**
     * Test authenticated users cannot access login page
     */
    public function test_authenticated_users_cannot_access_login_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirect('/dashboard');
    }

    /**
     * Test authenticated users are redirected when attempting to login again
     */
    public function test_authenticated_users_are_redirected_when_attempting_to_login_again(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
    }

    /**
     * Test login logs failed attempts
     */
    public function test_login_logs_failed_attempts(): void
    {
        // Mock AuthService to return authentication failure
        $authServiceMock = $this->mock(AuthService::class);
        $authServiceMock->shouldReceive('authenticateUser')
            ->once()
            ->with('test@example.com', 'wrongpassword')
            ->andReturn([
                'success' => false,
                'message' => 'Invalid credentials',
                'user' => null
            ]);

        // Mock LoggerService and expect logAuthEvent to be called with specific parameters
        $loggerServiceMock = $this->mock(LoggerService::class);
        $loggerServiceMock->shouldReceive('logAuthEvent')
            ->once()
            ->with('login_failed', \Mockery::on(function ($arg) {
                return $arg['email'] === 'test@example.com' 
                    && $arg['reason'] === 'invalid_credentials';
            }));

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}

