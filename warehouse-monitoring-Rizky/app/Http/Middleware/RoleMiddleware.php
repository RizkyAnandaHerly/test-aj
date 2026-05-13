<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage in routes:
     *   ->middleware('role:admin')
     *   ->middleware('role:admin,manager')   // allow either role
     *
     * @param  string  ...$roles  One or more allowed role names.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        // Guest check — let the 'auth' middleware handle unauthenticated users,
        // but guard here defensively.
        if (! $user) {
            abort(403, 'Unauthenticated.');
        }

        // Eager-load role only if not already loaded to avoid redundant queries.
        if (! $user->relationLoaded('role')) {
            $user->load('role');
        }

        // Check if the authenticated user's role is in the allowed list.
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Access denied. You do not have permission to view this page.');
    }
}
