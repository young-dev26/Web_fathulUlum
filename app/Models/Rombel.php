<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Rombel extends Model { protected $fillable = ['academic_year_id', 'unit', 'grade_level', 'name', 'homeroom_teacher_id']; public function students(): BelongsToMany { return $this->belongsToMany(Student::class, 'student_rombel')->withPivot(['starts_at', 'ends_at']); } public function assignments(): HasMany { return $this->hasMany(SubjectAssignment::class); } }