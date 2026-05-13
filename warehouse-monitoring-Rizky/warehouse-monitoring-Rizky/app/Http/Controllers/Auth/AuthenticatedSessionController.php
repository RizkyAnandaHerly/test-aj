<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * After authenticating, we eager-load the user's role (one extra query,
     * avoids N+1 inside hasRole()) then redirect to the role-specific dashboard.
     * Users with no role assigned fall back to the generic /dashboard.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Single eager-load so hasRole() reads from memory, not the DB.
        $user = Auth::user()->load('role');

        $destination = match (true) {
            $user->hasRole('admin')   => route('admin.dashboard'),
            $user->hasRole('manager') => route('manager.dashboard'),
            $user->hasRole('staff')   => route('staff.dashboard'),
            default                   => route('dashboard'),
        };

        return redirect()->intended($destination);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
