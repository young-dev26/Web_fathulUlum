<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class SubjectAssignment extends Model { protected $fillable = ['rombel_id', 'subject_id', 'teacher_id', 'academic_term_id']; public function rombel(): BelongsTo { return $this->belongsTo(Rombel::class); } public function subject(): BelongsTo { return $this->belongsTo(Subject::class); } public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); } public function schedules(): HasMany { return $this->hasMany(Schedule::class); } }