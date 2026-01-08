<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google
     */
    public function handleGoogleCallback()
    {
        try {
            // Get user info from Google
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists with this Google ID
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if ($user) {
                // User exists, update avatar if changed
                $user->update([
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Check if email already exists (registered via normal signup)
                $existingUser = User::where('email', $googleUser->getEmail())->first();
                
                if ($existingUser) {
                    // Link Google account to existing user
                    $existingUser->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                    $user = $existingUser;
                } else {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => Hash::make(uniqid()), // Random password
                        'role' => 'user', // Default role
                        'email_verified_at' => now(), // Auto verify email
                    ]);
                }
            }
            
            // Update last login
            $user->update(['last_login_at' => now()]);
            
            // Login the user
            Auth::login($user);
            
            // Redirect based on role
            if (in_array($user->role, ['siswa', 'mahasiswa'])) {
                return redirect()->route('siswa.dashboard')->with('success', 'Berhasil login dengan Google!');
            }
            
            return redirect()->route('dashboard')->with('success', 'Berhasil login dengan Google!');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}
