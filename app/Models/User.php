<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'role', 'unit'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function getAuthIdentifier(): string
    {
        return 'admin:'.$this->getKey();
    }

    public function recordedAttendances(): HasMany { return $this->hasMany(Attendance::class, 'recorded_by_user_id'); }

    public function effectiveRole(): ?string
    {
        return $this->role === 'admin' ? 'admin' : null;
    }

    public function isAdmin(): bool
    {
        return $this->effectiveRole() === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->effectiveRole() === 'siswa';
    }

    public function isTeacher(): bool
    {
        return $this->effectiveRole() === 'guru';
    }

    public function isParent(): bool
    {
        return $this->effectiveRole() === 'orang_tua';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
