<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class BackfillLastLogin
{
    /**
     * Handle an incoming request.
     * If the authenticated user has no last_login_at yet (legacy accounts),
     * set it to now once, without changing it on every request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Schema::hasColumn('users', 'last_login_at')) {
            $user = Auth::user();
            if (empty($user->last_login_at)) {
                $user->last_login_at = now();
                // Save quietly to avoid touching updated_at excessively
                $user->timestamps = false;
                $user->save();
                $user->timestamps = true;
            }
        }
        return $next($request);
    }
}
