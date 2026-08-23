<?php

namespace App\Auth;

use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\StaffTu;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

class RoleUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        [$type, $id] = array_pad(explode(':', (string) $identifier, 2), 2, null);
        $model = match ($type) {
            'admin' => User::class,
            'siswa' => Student::class,
            'guru' => Teacher::class,
            'staff_tu' => StaffTu::class,
            'orang_tua' => ParentProfile::class,
            default => null,
        };

        return $model && $id ? $model::find($id) : null;
    }

    public function retrieveByToken($identifier, #[\SensitiveParameter] $token)
    {
        $user = $this->retrieveById($identifier);
        return $user && hash_equals((string) $user->getRememberToken(), (string) $token) ? $user : null;
    }

    public function updateRememberToken(Authenticatable $user, #[\SensitiveParameter] $token): void
    {
        $user->setRememberToken($token);
        $user->save();
    }

    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials)
    {
        $role = $credentials['role'] ?? null;
        $identifier = trim((string) ($credentials['identifier'] ?? ''));
        if (! $role && ! empty($credentials['email'])) {
            foreach ([User::class, Student::class, Teacher::class, StaffTu::class, ParentProfile::class] as $model) {
                $user = $model::where('email', $credentials['email'])->first();
                if ($user) {
                    return $user;
                }
            }
        }
        if (! $role || $identifier === '') {
            return null;
        }

        return match ($role) {
            'admin' => User::where('email', $identifier)->where('role', 'admin')->first(),
            'staff_tu' => StaffTu::where('nip', $identifier)->first(),
            'guru' => Teacher::where('nip', $identifier)->orWhere('nip_nuptk', $identifier)->first(),
            'siswa' => Student::where('nis', $identifier)->orWhere('nisn', $identifier)->first(),
            'orang_tua' => ParentProfile::where('nomor_hp', $identifier)->orWhere('telepon', $identifier)->first(),
            default => null,
        };
    }

    public function validateCredentials(Authenticatable $user, #[\SensitiveParameter] array $credentials): bool
    {
        return Hash::check($credentials['password'] ?? '', $user->getAuthPassword());
    }

    public function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials, bool $force = false): void
    {
        if ($force || Hash::needsRehash($user->getAuthPassword())) {
            $user->forceFill(['password' => Hash::make($credentials['password'])])->save();
        }
    }
}