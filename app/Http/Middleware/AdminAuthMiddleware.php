<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Please login first.');
        }

        $user = Auth::user();

        if (!$user->is_active || !in_array($user->role, ['admin', 'super_admin'])) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->with('error', 'Your account is not allowed to access admin panel.');
        }

        return $next($request);
    }
}