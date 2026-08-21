<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20)->unique();
            $table->string('nik', 20)->nullable()->unique();
            $table->string('qr_code_key', 64)->unique();
            $table->string('nama_lengkap');
            $table->enum('unit', ['mi', 'mts']);
            $table->unsignedTinyInteger('kelas');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            $table->index(['unit', 'kelas']);
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nip_nuptk', 30)->nullable()->unique();
            $table->string('nama_lengkap');
            $table->enum('unit', ['mi', 'mts', 'yayasan']);
            $table->string('mata_pelajaran')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'guru', 'siswa'])->default('siswa')->after('email');
            $table->enum('unit', ['mi', 'mts', 'yayasan'])->default('yayasan')->after('role');
            $table->foreignId('student_id')->nullable()->after('unit')->constrained('students')->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->after('student_id')->constrained('teachers')->nullOnDelete();
            $table->index(['role', 'unit']);
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha'])->default('Hadir');
            $table->enum('metode', ['QR_Scan', 'Manual'])->default('QR_Scan');
            $table->text('keterangan')->nullable();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['student_id', 'tanggal']);
            $table->index(['tanggal', 'status']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedBigInteger('nominal');
            $table->enum('status_bayar', ['lunas', 'belum_lunas'])->default('belum_lunas');
            $table->timestamps();
            $table->unique(['student_id', 'bulan', 'tahun']);
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('semester');
            $table->string('tahun_ajaran', 20);
            $table->string('mata_pelajaran');
            $table->decimal('nilai_pengetahuan', 5, 2)->nullable();
            $table->decimal('nilai_keterampilan', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'semester', 'tahun_ajaran', 'mata_pelajaran'], 'grades_period_subject_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('attendances');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['role', 'unit', 'student_id', 'teacher_id']);
        });
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('students');
    }
};
