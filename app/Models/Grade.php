<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'semester', 'tahun_ajaran', 'mata_pelajaran', 'nilai_pengetahuan', 'nilai_keterampilan'];
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
}
