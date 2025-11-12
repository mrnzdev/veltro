<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\LoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    public function __construct(
        private readonly LoggerService $logger
    ) {}

    /**
     * Display the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Log password reset request
        $this->logger->logAuthEvent('password_reset_requested', [
            'email' => $request->email,
        ]);

        // Send password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            // Log successful email sent
            $this->logger->logAuthEvent('password_reset_link_sent', [
                'email' => $request->email,
            ]);

            return back()->with('status', __($status));
        }

        // Log failed reset attempt
        $this->logger->logAuthEvent('password_reset_failed', [
            'email' => $request->email,
            'reason' => 'user_not_found',
        ]);

        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset form
     */
    public function showResetPasswordForm(string $token, Request $request)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        // Attempt to reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                // Log successful password reset
                $this->logger->logAuthEvent('password_reset_completed', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);

                // Log the user in
                Auth::login($user);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('dashboard')->with('status', 'Tu contraseña ha sido restablecida exitosamente.');
        }

        // Log failed password reset
        $this->logger->logAuthEvent('password_reset_failed', [
            'email' => $request->email,
            'reason' => 'invalid_token',
        ]);

        return back()->withErrors(['email' => __($status)]);
    }
}

