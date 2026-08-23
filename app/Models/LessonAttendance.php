<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class LessonAttendance extends Model { protected $fillable = ['schedule_session_id', 'student_id', 'status', 'check_in_at', 'note', 'recorded_by_type', 'recorded_by_id']; public function session(): BelongsTo { return $this->belongsTo(ScheduleSession::class, 'schedule_session_id'); } public function student(): BelongsTo { return $this->belongsTo(Student::class); } }