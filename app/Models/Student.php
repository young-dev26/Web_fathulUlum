<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory;

    public function getAuthIdentifier(): string
    {
        return 'siswa:'.$this->getKey();
    }

    protected $fillable = ['email', 'password', 'nisn', 'nik', 'qr_code_key', 'nama_lengkap', 'unit', 'kelas', 'jenis_kelamin', 'status_aktif'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['status_aktif' => 'boolean', 'password' => 'hashed'];
    }

    public function effectiveRole(): string { return 'siswa'; }
    public function student(): self { return $this; }
    public function attendances(): HasMany { return $this->hasMany(Attendance::class); }
    public function leaveRequests(): HasMany { return $this->hasMany(StudentLeaveRequest::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
    public function grades(): HasMany { return $this->hasMany(Grade::class); }
}
