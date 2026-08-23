<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ScheduleSession extends Model { protected $fillable = ['schedule_id', 'session_date', 'status', 'opened_by']; protected function casts(): array { return ['session_date' => 'date']; } public function schedule(): BelongsTo { return $this->belongsTo(Schedule::class); } public function attendances(): HasMany { return $this->hasMany(LessonAttendance::class); } }