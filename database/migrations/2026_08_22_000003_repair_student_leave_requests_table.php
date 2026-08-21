<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('student_leave_requests')) {
            return;
        }

        Schema::create('student_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis_izin', ['sakit', 'izin', 'alpha', 'lainnya'])->default('izin');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('keterangan');
            $table->string('lampiran')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('reviewed_by_type')->nullable();
            $table->unsignedBigInteger('reviewed_by_id')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->index(['student_id', 'status']);
            $table->index(['reviewed_by_type', 'reviewed_by_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_leave_requests');
    }
};