<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_applications', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();
            $table->string('nama_lengkap');
            $table->string('nisn', 20)->nullable();
            $table->string('nama_wali');
            $table->string('email')->nullable();
            $table->string('telepon', 30);
            $table->enum('unit', ['mi', 'mts']);
            $table->unsignedTinyInteger('kelas_tujuan');
            $table->text('alamat');
            $table->enum('status', ['baru', 'diverifikasi', 'diterima', 'ditolak'])->default('baru');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->index(['status', 'unit']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_applications');
    }
};