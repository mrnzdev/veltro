<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\LoggerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly LoggerService $logger
    ) {}

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            $this->logger->logAuthEvent('oauth_redirect_failed', [
                'provider' => 'google',
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('login')->withErrors([
                'email' => 'No se pudo conectar con Google. Por favor, intenta de nuevo.',
            ]);
        }
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find or create user
            $result = $this->authService->findOrCreateOAuthUser(
                $googleUser->getId(),
                $googleUser->getEmail(),
                $googleUser->getName(),
                $googleUser->getAvatar()
            );

            if (!$result['success']) {
                $this->logger->logAuthEvent('oauth_callback_failed', [
                    'provider' => 'google',
                    'email' => $googleUser->getEmail(),
                    'error' => $result['message'] ?? 'Unknown error',
                ]);

                return redirect()->route('login')->withErrors([
                    'email' => 'Hubo un error al procesar tu cuenta de Google. Por favor, intenta de nuevo.',
                ]);
            }

            // Get the user model and log them in
            $user = User::find($result['user_id']);
            Auth::login($user, true); // true = remember me

            // Log the successful OAuth authentication
            $logData = [
                'user_id' => $user->id,
                'email' => $user->email,
                'provider' => 'google',
            ];

            if ($result['is_new']) {
                $this->logger->logAuthEvent('oauth_user_registered', $logData);
                $message = '¡Bienvenido! Tu cuenta ha sido creada exitosamente.';
                $redirectRoute = 'dashboard';
            } elseif ($result['linked']) {
                $this->logger->logAuthEvent('oauth_account_linked', $logData);
                $message = '¡Cuenta de Google vinculada exitosamente!';
                $redirectRoute = 'profile.show'; // Redirect to profile after linking
            } else {
                $this->logger->logAuthEvent('oauth_user_login', $logData);
                $message = '¡Bienvenido de nuevo!';
                $redirectRoute = 'dashboard';
            }

            return redirect()->route($redirectRoute)->with('success', $message);
        } catch (\Exception $e) {
            $this->logger->logAuthEvent('oauth_callback_exception', [
                'provider' => 'google',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')->withErrors([
                'email' => 'Error al autenticar con Google. Por favor, intenta de nuevo más tarde.',
            ]);
        }
    }
}

