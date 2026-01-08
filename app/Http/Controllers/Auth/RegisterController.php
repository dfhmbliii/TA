<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'max:50', 'unique:siswas,nisn'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'jurusan_sma' => ['required', 'string', 'max:255'],
            'asal_sekolah' => ['required', 'string', 'max:255'],
            'tahun_lulus' => ['required', 'digits:4'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        // Create siswa profile
        \App\Models\Siswa::create([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'email' => $request->email,
            'jurusan_sma' => $request->jurusan_sma,
            'asal_sekolah' => $request->asal_sekolah,
            'tahun_lulus' => $request->tahun_lulus,
        ]);

        Auth::login($user);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Selamat datang! Akun Anda berhasil dibuat.');
    }
}