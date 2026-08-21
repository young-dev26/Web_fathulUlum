<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'tanggal', 'jam_masuk', 'status', 'metode', 'keterangan', 'recorded_by_user_id', 'recorded_by_type', 'recorded_by_id'];

    protected function casts(): array { return ['tanggal' => 'date']; }

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function recorder(): MorphTo { return $this->morphTo(__FUNCTION__, 'recorded_by_type', 'recorded_by_id'); }
}
