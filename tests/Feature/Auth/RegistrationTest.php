<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\AuthService;
use App\Services\LoggerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registration page can be displayed
     */
    public function test_registration_page_can_be_displayed(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test users can register with valid information
     */
    public function test_users_can_register_with_valid_information(): void
    {
        // Mock AuthService
        $authServiceMock = $this->mock(AuthService::class);
        $authServiceMock->shouldReceive('checkEmailUnique')
            ->once()
            ->with('test@example.com')
            ->andReturn(true);
        
        $authServiceMock->shouldReceive('registerUser')
            ->once()
            ->with('Test User', 'test@example.com', 'password123')
            ->andReturn([
                'user_id' => 1,
                'success' => true,
                'message' => 'User registered successfully'
            ]);

        // Mock LoggerService
        $loggerServiceMock = $this->mock(LoggerService::class);
        $loggerServiceMock->shouldReceive('logAuthEvent')
            ->once()
            ->with('user_registered', \Mockery::type('array'));

        // Create user in database (simulating what would happen after registration)
        $user = User::factory()->create([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', '¡Cuenta creada exitosamente!');
        $this->assertAuthenticated();
    }

    /**
     * Test registration fails when name is missing
     */
    public function test_registration_fails_when_name_is_missing(): void
    {
        $response = $this->post('/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertGuest();
    }

    /**
     * Test registration fails when email is missing
     */
    public function test_registration_fails_when_email_is_missing(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test registration fails when password is missing
     */
    public function test_registration_fails_when_password_is_missing(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    /**
     * Test registration fails when password confirmation does not match
     */
    public function test_registration_fails_when_password_confirmation_does_not_match(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    /**
     * Test registration fails with invalid email format
     */
    public function test_registration_fails_with_invalid_email_format(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /**
     * Test registration fails with weak password
     */
    public function test_registration_fails_with_weak_password(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    /**
     * Test registration fails when email already exists
     */
    public function test_registration_fails_when_email_already_exists(): void
    {
        // Mock AuthService to return email not unique
        $authServiceMock = $this->mock(AuthService::class);
        $authServiceMock->shouldReceive('checkEmailUnique')
            ->once()
            ->with('existing@example.com')
            ->andReturn(false);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'El email ya está registrado.']);
        $this->assertGuest();
    }

    /**
     * Test registration fails when service returns failure
     */
    public function test_registration_fails_when_service_returns_failure(): void
    {
        // Mock AuthService
        $authServiceMock = $this->mock(AuthService::class);
        $authServiceMock->shouldReceive('checkEmailUnique')
            ->once()
            ->with('test@example.com')
            ->andReturn(true);
        
        $authServiceMock->shouldReceive('registerUser')
            ->once()
            ->with('Test User', 'test@example.com', 'password123')
            ->andReturn([
                'user_id' => 0,
                'success' => false,
                'message' => 'Database error occurred'
            ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'Database error occurred']);
        $this->assertGuest();
    }

    /**
     * Test authenticated users cannot access registration page
     */
    public function test_authenticated_users_cannot_access_registration_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect('/dashboard');
    }

    /**
     * Test authenticated users cannot register
     */
    public function test_authenticated_users_cannot_register(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/register', [
            'name' => 'Another User',
            'email' => 'another@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
    }
}

