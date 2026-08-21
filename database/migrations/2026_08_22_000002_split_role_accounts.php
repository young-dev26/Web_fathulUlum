<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['students', 'teachers', 'parents'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (! Schema::hasColumn($table->getTable(), 'email')) {
                    $table->string('email')->nullable()->unique();
                }
                if (! Schema::hasColumn($table->getTable(), 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable();
                }
                if (! Schema::hasColumn($table->getTable(), 'password')) {
                    $table->string('password')->nullable();
                }
                if (! Schema::hasColumn($table->getTable(), 'remember_token')) {
                    $table->rememberToken();
                }
            });
        }

        foreach (['students', 'teachers', 'parents'] as $tableName) {
            if (Schema::hasColumn($tableName, 'user_id')) {
                DB::table($tableName)->whereNotNull('user_id')->orderBy('id')->each(function ($profile) use ($tableName) {
                    $account = DB::table('users')->where('id', $profile->user_id)->first();
                    if ($account) {
                        DB::table($tableName)->where('id', $profile->id)->update([
                            'email' => $account->email,
                            'password' => $account->password,
                            'email_verified_at' => $account->email_verified_at,
                        ]);
                    }
                });
            }
        }

        if (Schema::hasTable('student_leave_requests')) {
            DB::table('student_leave_requests')->update(['reviewed_by_user_id' => null]);
            Schema::table('student_leave_requests', function (Blueprint $table) {
                if (! Schema::hasColumn('student_leave_requests', 'reviewed_by_type')) {
                    $table->string('reviewed_by_type')->nullable()->after('reviewed_by_user_id');
                }
                if (! Schema::hasColumn('student_leave_requests', 'reviewed_by_id')) {
                    $table->unsignedBigInteger('reviewed_by_id')->nullable()->after('reviewed_by_type');
                }
            });
        }
        if (Schema::hasTable('attendances')) {
            DB::table('attendances')->update(['recorded_by_user_id' => null]);
            Schema::table('attendances', function (Blueprint $table) {
                if (! Schema::hasColumn('attendances', 'recorded_by_type')) {
                    $table->string('recorded_by_type')->nullable()->after('recorded_by_user_id');
                }
                if (! Schema::hasColumn('attendances', 'recorded_by_id')) {
                    $table->unsignedBigInteger('recorded_by_id')->nullable()->after('recorded_by_type');
                }
            });
        }
        DB::table('users')->where('role', '!=', 'admin')->delete();

        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropIndex(['user_id', 'unit']);
                $table->dropColumn('user_id');
            }
        });
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropIndex(['user_id', 'unit']);
                $table->dropColumn('user_id');
            }
        });
        Schema::table('parents', function (Blueprint $table) {
            if (Schema::hasColumn('parents', 'user_id')) {
                if (DB::getDriverName() === 'mysql') {
                    $table->dropForeign(['user_id']);
                    $table->dropUnique(['user_id']);
                } else {
                    $table->dropUnique(['user_id']);
                    $table->dropForeign(['user_id']);
                }
                $table->dropColumn('user_id');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin') NOT NULL DEFAULT 'admin'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'guru', 'siswa', 'orang_tua') NOT NULL DEFAULT 'admin'");
        }

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        });
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        });
        Schema::table('parents', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->cascadeOnDelete();
        });

        foreach (['students', 'teachers', 'parents'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropUnique([$table->getTable() . '_email_unique']);
                $table->dropColumn(['email', 'email_verified_at', 'password', 'remember_token']);
            });
        }
    }
};