<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use HasFactory;

    public function getAuthIdentifier(): string
    {
        return 'guru:'.$this->getKey();
    }

    protected $fillable = ['email', 'password', 'nip', 'nip_nuptk', 'nama_lengkap', 'unit', 'mata_pelajaran'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function effectiveRole(): string { return 'guru'; }
}
