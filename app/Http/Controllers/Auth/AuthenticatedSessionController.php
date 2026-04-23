<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Handle Remember Email cookie
        if ($request->boolean('remember')) {
            Cookie::queue('remember_email', $request->email, 60 * 24 * 30); // 30 days
        } else {
            Cookie::queue(Cookie::forget('remember_email'));
        }

        $role = $request->user()->role;

        return match ($role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'guru' => redirect()->intended(route('guru.dashboard')),
            'orang_tua' => redirect()->intended(route('parent.dashboard')),
            default => redirect()->intended(route('dashboard')),
        };
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
