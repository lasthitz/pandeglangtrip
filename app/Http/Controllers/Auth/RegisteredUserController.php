<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['nullable', 'in:user,pengelola'], // T3: admin tidak boleh register
    ]);

    $role = $request->input('role', 'user'); // default user

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $role,
    ]);

    event(new Registered($user));

    Auth::login($user);

    // redirect sesuai role
    return match ($role) {
        'pengelola' => redirect('/panel'),
        default => redirect('/dashboard'),
    };
}


    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect('/admin'),
            'pengelola' => redirect('/panel'),
            default => redirect('/dashboard'), // aman & sederhana (Breeze default)
        };
    }
}
