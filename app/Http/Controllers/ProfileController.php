<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {   
        return view('profile.show');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile.show')->with('success', '¡Perfil actualizado exitosamente!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // If user has a password, they must provide the current one
        // If user is OAuth-only (no password), they can set one without providing current
        $rules = [
            'password' => ['required', 'confirmed', Password::defaults()],
        ];

        if ($user->password) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $request->validate($rules);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $message = $user->password ? '¡Contraseña actualizada exitosamente!' : '¡Contraseña establecida exitosamente!';
        
        return redirect()->route('profile.show')->with('success', $message);
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'confirmation' => ['required', 'in:ELIMINAR'],
        ]);

        $user = Auth::user();

        // Logout the user
        Auth::logout();

        // Delete the user account
        $user->delete();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Tu cuenta ha sido eliminada permanentemente.');
    }
}
