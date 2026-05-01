<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware checks if the logged-in user has the correct role.
 * If they don't, they get redirected away.
 *
 * Usage in routes: ->middleware('role:teacher')
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if the user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if the user has the correct role
        if (auth()->user()->role !== $role) {
            // Wrong role - redirect to their correct dashboard
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access that page.');
        }

        // All good - continue to the page
        return $next($request);
    }
}
