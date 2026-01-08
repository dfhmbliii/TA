<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Treat 'siswa' and 'mahasiswa' as equivalent
        $userRole = in_array($user->role, ['siswa', 'mahasiswa']) ? 'siswa' : $user->role;
        $checkRole = in_array($role, ['siswa', 'mahasiswa']) ? 'siswa' : $role;

        if ($userRole !== $checkRole) {
            if (in_array($user->role, ['siswa', 'mahasiswa'])) {
                return redirect()->route('siswa.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
