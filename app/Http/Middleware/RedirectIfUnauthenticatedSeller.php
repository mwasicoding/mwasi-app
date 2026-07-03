<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfUnauthenticatedSeller
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If the user is not authenticated through the seller guard, block them
        if (!Auth::guard('seller')->check()) {
            return redirect()->route('login')->withErrors([
                'login_input' => 'Please sign in to access the seller terminal.',
            ]);
        }

        // 2. Extra Security: If they are logged in but the owner suspended them, boot them out instantly
        if (!Auth::guard('seller')->user()->is_active) {
            Auth::guard('seller')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'login_input' => 'Your employee terminal access has been suspended by the manager.',
            ]);
        }

        return $next($request);
    }
}