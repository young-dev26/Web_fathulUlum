<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StudentLeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'jenis_izin',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'lampiran',
        'status',
        'reviewed_by_user_id',
        'reviewed_by_type',
        'reviewed_by_id',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewer(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'reviewed_by_type', 'reviewed_by_id');
    }
}
