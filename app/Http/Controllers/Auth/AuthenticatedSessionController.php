<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Default redirect by role
        $defaultTarget = match ($user->role ?? 'user') {
            'admin'     => '/admin/tickets',
            'pengelola' => '/panel/dashboard',
            default     => '/',
        };

        /**
         * PENTING:
         * - Kalau ada intended URL (misal dari /tickets/1 atau /tours/1)
         * - Laravel akan otomatis redirect ke sana
         */
        return redirect()->intended($defaultTarget);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
