<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Schedule extends Model { protected $fillable = ['subject_assignment_id', 'schedule_slot_id', 'room', 'starts_on', 'ends_on', 'is_active']; protected function casts(): array { return ['starts_on' => 'date', 'ends_on' => 'date', 'is_active' => 'boolean']; } public function assignment(): BelongsTo { return $this->belongsTo(SubjectAssignment::class, 'subject_assignment_id'); } public function slot(): BelongsTo { return $this->belongsTo(ScheduleSlot::class, 'schedule_slot_id'); } public function sessions(): HasMany { return $this->hasMany(ScheduleSession::class); } }