<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StaffTu extends Authenticatable
{
    use HasFactory;

    protected $table = 'staff_tu';
    protected $fillable = ['nip', 'nama_lengkap', 'email', 'password', 'unit', 'jabatan'];
    protected $hidden = ['password', 'remember_token'];

    public function getAuthIdentifier(): string
    {
        return 'staff_tu:'.$this->getKey();
    }

    public function effectiveRole(): string { return 'staff_tu'; }

    protected function casts(): array
    {
        return ['password' => 'hashed', 'email_verified_at' => 'datetime'];
    }
}