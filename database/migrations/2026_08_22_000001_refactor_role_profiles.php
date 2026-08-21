<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->index(['user_id', 'unit']);
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->index(['user_id', 'unit']);
        });

        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nama_lengkap');
            $table->string('hubungan')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'student_id')) {
                $table->dropForeign(['student_id']);
                $table->dropColumn('student_id');
            }

            if (Schema::hasColumn('users', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
                $table->dropColumn('teacher_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->after('unit')->constrained('students')->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->after('student_id')->constrained('teachers')->nullOnDelete();
        });

        Schema::dropIfExists('parents');

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropIndex(['user_id', 'unit']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropIndex(['user_id', 'unit']);
        });
    }
};
