<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('dashboard.profile', ['user' => auth()->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $role = $user->effectiveRole();
        $rules = ['nama_lengkap' => ['required', 'string', 'max:255'], 'email' => ['nullable', 'email', 'max:255']];
        $credentialField = match ($role) {
            'admin' => 'email',
            'staff_tu', 'guru' => 'nip',
            'siswa' => 'nis',
            'orang_tua' => 'nomor_hp',
            default => null,
        };

        if ($credentialField && $credentialField !== 'email') {
            $rules[$credentialField] = ['required', 'string', 'max:30', 'unique:'.$user->getTable().','.$credentialField.','.$user->getKey()];
        }
        $validated = $request->validate($rules + ['password' => ['nullable', 'string', 'min:8', 'confirmed']]);

        if ($role === 'admin') {
            $user->fill(['name' => $validated['nama_lengkap'], 'email' => $validated['email']]);
        } else {
            $user->fill(['nama_lengkap' => $validated['nama_lengkap'], 'email' => $validated['email'] ?? null]);
            if ($credentialField !== 'email') {
                $user->{$credentialField} = $validated[$credentialField];
            }
        }
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return back()->with('status', 'Profil berhasil diperbarui.');
    }
}