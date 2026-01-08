<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // update last_login_at in a best-effort manner
            try {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                $user->last_login_at = now();
                $user->save();
            } catch (\Throwable $e) {
                // silently ignore to avoid blocking login if column doesn't exist yet
            }
            return redirect()->intended('/dashboard')->with('success', 'Login berhasil! Selamat datang di Pilihanku.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah. Silakan periksa kembali kredensial Anda.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
