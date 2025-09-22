<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            // إضافة العمود بعد university_id (اختياري)
            $table->unsignedBigInteger('branch_id')->nullable()->after('university_id');

            // مفتاح أجنبي إلى university_branches(id) مع ON DELETE SET NULL
            $table->foreign('branch_id')
                  ->references('id')
                  ->on('university_branches')
                  ->onDelete('set null');

            // فهرس مساعد للاستعلامات الشائعة
            $table->index(['university_id', 'branch_id']);
        });
    }

    public function down(): void
    {
        Schema::table('activation_codes', function (Blueprint $table) {
            $table->dropIndex(['university_id', 'branch_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};
