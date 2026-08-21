<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('student_leave_requests')) {
            return;
        }

        Schema::table('student_leave_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('student_leave_requests', 'reviewed_by_type')) {
                $table->string('reviewed_by_type')->nullable()->after('status');
            }
            if (! Schema::hasColumn('student_leave_requests', 'reviewed_by_id')) {
                $table->unsignedBigInteger('reviewed_by_id')->nullable()->after('reviewed_by_type');
            }
        });

        if (Schema::hasColumn('student_leave_requests', 'reviewed_by_user_id')) {
            DB::table('student_leave_requests')->whereNotNull('reviewed_by_user_id')->update([
                'reviewed_by_type' => 'App\\Models\\User',
                'reviewed_by_id' => DB::raw('reviewed_by_user_id'),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('student_leave_requests', function (Blueprint $table) {
            if (Schema::hasColumn('student_leave_requests', 'reviewed_by_type')) {
                $table->dropColumn('reviewed_by_type');
            }
            if (Schema::hasColumn('student_leave_requests', 'reviewed_by_id')) {
                $table->dropColumn('reviewed_by_id');
            }
        });
    }
};