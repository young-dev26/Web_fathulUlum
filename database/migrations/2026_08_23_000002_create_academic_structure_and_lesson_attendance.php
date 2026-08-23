<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id(); $table->string('name', 20)->unique(); $table->date('starts_at'); $table->date('ends_at'); $table->boolean('is_active')->default(false); $table->timestamps();
        });
        Schema::create('academic_terms', function (Blueprint $table) {
            $table->id(); $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete(); $table->unsignedTinyInteger('semester'); $table->date('starts_at'); $table->date('ends_at'); $table->timestamps(); $table->unique(['academic_year_id', 'semester']);
        });
        Schema::create('rombels', function (Blueprint $table) {
            $table->id(); $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete(); $table->enum('unit', ['mi', 'mts']); $table->unsignedTinyInteger('grade_level'); $table->string('name', 30); $table->foreignId('homeroom_teacher_id')->nullable()->constrained('teachers')->nullOnDelete(); $table->timestamps(); $table->unique(['academic_year_id', 'unit', 'name']);
        });
        Schema::create('student_rombel', function (Blueprint $table) {
            $table->foreignId('student_id')->constrained()->cascadeOnDelete(); $table->foreignId('rombel_id')->constrained()->cascadeOnDelete(); $table->date('starts_at')->nullable(); $table->date('ends_at')->nullable(); $table->timestamps(); $table->primary(['student_id', 'rombel_id']);
        });
        Schema::create('subjects', function (Blueprint $table) {
            $table->id(); $table->string('code', 30)->unique(); $table->string('name'); $table->enum('unit', ['mi', 'mts', 'yayasan']); $table->boolean('is_active')->default(true); $table->timestamps();
        });
        Schema::create('subject_assignments', function (Blueprint $table) {
            $table->id(); $table->foreignId('rombel_id')->constrained()->cascadeOnDelete(); $table->foreignId('subject_id')->constrained()->restrictOnDelete(); $table->foreignId('teacher_id')->constrained()->restrictOnDelete(); $table->foreignId('academic_term_id')->constrained()->cascadeOnDelete(); $table->timestamps(); $table->unique(['rombel_id', 'subject_id', 'teacher_id', 'academic_term_id'], 'subject_assignment_unique');
        });
        Schema::create('schedule_slots', function (Blueprint $table) {
            $table->id(); $table->unsignedTinyInteger('day_of_week'); $table->unsignedTinyInteger('period_number'); $table->time('starts_at'); $table->time('ends_at'); $table->timestamps(); $table->unique(['day_of_week', 'period_number']);
        });
        Schema::create('schedules', function (Blueprint $table) {
            $table->id(); $table->foreignId('subject_assignment_id')->constrained()->cascadeOnDelete(); $table->foreignId('schedule_slot_id')->constrained()->restrictOnDelete(); $table->string('room', 50)->nullable(); $table->date('starts_on')->nullable(); $table->date('ends_on')->nullable(); $table->boolean('is_active')->default(true); $table->timestamps(); $table->unique(['subject_assignment_id', 'schedule_slot_id']);
        });
        Schema::create('schedule_sessions', function (Blueprint $table) {
            $table->id(); $table->foreignId('schedule_id')->constrained()->cascadeOnDelete(); $table->date('session_date'); $table->enum('status', ['open', 'closed'])->default('open'); $table->foreignId('opened_by')->nullable()->constrained('teachers')->nullOnDelete(); $table->timestamps(); $table->unique(['schedule_id', 'session_date']);
        });
        Schema::create('lesson_attendances', function (Blueprint $table) {
            $table->id(); $table->foreignId('schedule_session_id')->constrained()->cascadeOnDelete(); $table->foreignId('student_id')->constrained()->cascadeOnDelete(); $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha'])->default('Hadir'); $table->time('check_in_at')->nullable(); $table->text('note')->nullable(); $table->string('recorded_by_type')->nullable(); $table->unsignedBigInteger('recorded_by_id')->nullable(); $table->timestamps(); $table->unique(['schedule_session_id', 'student_id']); $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_attendances'); Schema::dropIfExists('schedule_sessions'); Schema::dropIfExists('schedules'); Schema::dropIfExists('schedule_slots'); Schema::dropIfExists('subject_assignments'); Schema::dropIfExists('subjects'); Schema::dropIfExists('student_rombel'); Schema::dropIfExists('rombels'); Schema::dropIfExists('academic_terms'); Schema::dropIfExists('academic_years');
    }
};