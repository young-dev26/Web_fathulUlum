<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ScheduleSlot extends Model { protected $fillable = ['day_of_week', 'period_number', 'starts_at', 'ends_at']; public function schedules(): HasMany { return $this->hasMany(Schedule::class); } }