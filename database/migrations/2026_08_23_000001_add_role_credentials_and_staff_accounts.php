<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('nis', 20)->nullable()->unique()->after('nisn');
        });
        DB::table('students')->whereNull('nis')->update(['nis' => DB::raw('nisn')]);

        Schema::table('teachers', function (Blueprint $table) {
            $table->string('nip', 30)->nullable()->unique()->after('nip_nuptk');
        });
        DB::table('teachers')->whereNull('nip')->update(['nip' => DB::raw('nip_nuptk')]);

        Schema::table('parents', function (Blueprint $table) {
            $table->string('nomor_hp', 30)->nullable()->unique()->after('telepon');
        });
        DB::table('parents')->whereNull('nomor_hp')->update(['nomor_hp' => DB::raw('telepon')]);

        Schema::create('staff_tu', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 30)->unique();
            $table->string('nama_lengkap');
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->string('unit')->default('yayasan');
            $table->string('jabatan')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->index('unit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_tu');
        Schema::table('parents', function (Blueprint $table) {
            $table->dropUnique(['nomor_hp']);
            $table->dropColumn('nomor_hp');
        });
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropUnique(['nip']);
            $table->dropColumn('nip');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['nis']);
            $table->dropColumn('nis');
        });
    }
};