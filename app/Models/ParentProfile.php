<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ParentProfile extends Authenticatable
{
    use HasFactory;

    public function getAuthIdentifier(): string
    {
        return 'orang_tua:'.$this->getKey();
    }

    protected $table = 'parents';

    protected $fillable = [
        'email',
        'password',
        'nama_lengkap',
        'hubungan',
        'telepon',
        'nomor_hp',
        'email',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function effectiveRole(): string { return 'orang_tua'; }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id')->orderBy('nama_lengkap');
    }
}
