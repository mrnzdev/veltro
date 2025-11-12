<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\LoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly LoggerService $logger
    ) {}

    /**
     * Show the registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Check if email is unique using stored procedure
        if (!$this->authService->checkEmailUnique($request->email)) {
            return back()->withErrors([
                'email' => 'El email ya está registrado.'
            ])->onlyInput('email');
        }

        // Register user using stored procedure
        $result = $this->authService->registerUser(
            $request->name,
            $request->email,
            $request->password
        );

        if (!$result['success']) {
            return back()->withErrors([
                'email' => $result['message']
            ])->onlyInput('email');
        }

        // Get the user and log them in
        $user = User::find($result['user_id']);
        Auth::login($user);

        // Log successful registration
        $this->logger->logAuthEvent('user_registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
        ]);

        // Redirect to dashboard with success message
        return redirect()->route('dashboard')->with('success', '¡Cuenta creada exitosamente!');
    }

    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Authenticate user using stored procedure
        $result = $this->authService->authenticateUser(
            $credentials['email'],
            $credentials['password']
        );

        if ($result['success']) {
            // Get the user model and log them in
            $user = User::find($result['user']['id']);
            Auth::login($user, $request->boolean('remember'));

            $request->session()->regenerate();

            // Log successful login
            $this->logger->logAuthEvent('user_login', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return redirect()->intended(route('dashboard'));
        }

        // Log failed login attempt
        $this->logger->logAuthEvent('login_failed', [
            'email' => $credentials['email'],
            'reason' => 'invalid_credentials',
        ]);

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log logout event
        if ($user) {
            $this->logger->logAuthEvent('user_logout', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
