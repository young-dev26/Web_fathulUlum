<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
            ]);
        }

        $request->session()->put(
            Auth::guard()->getName(),
            Auth::user()->getAuthIdentifier(),
        );
        $request->session()->save();

        return match ($request->user()->effectiveRole()) {
            'siswa' => redirect()->route('dashboard.student.leave-requests.index'),
            default => redirect()->route('dashboard'),
        };
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Anda telah berhasil keluar dari portal.');
    }
}
