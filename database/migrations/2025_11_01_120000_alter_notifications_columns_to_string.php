<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // حوّل أعمدة النوع إلى نص لتجنّب مشاكل ENUM/SET عند إضافة أنواع جديدة
        // نستخدم change() إن توفر DBAL، وإلا نستعمل أوامر SQL مباشرة.
        try {
            Schema::table('notifications', function (Blueprint $table) {
                $table->string('type', 50)->default('system')->change();
                $table->string('target_type', 50)->nullable()->change();
            });
        } catch (\Throwable $e) {
            // fallback: تعديل مباشر عبر SQL (MySQL)
            try {
                DB::statement("ALTER TABLE `notifications` MODIFY COLUMN `type` VARCHAR(50) NOT NULL DEFAULT 'system'");
            } catch (\Throwable $e2) {}
            try {
                DB::statement("ALTER TABLE `notifications` MODIFY COLUMN `target_type` VARCHAR(50) NULL");
            } catch (\Throwable $e3) {}
        }
    }

    public function down(): void
    {
        // نُبقيها كسلاسل نصية لتجنب كسر أنواع جديدة تم تخزينها
        try {
            Schema::table('notifications', function (Blueprint $table) {
                $table->string('type', 50)->default('system')->change();
                $table->string('target_type', 50)->nullable()->change();
            });
        } catch (\Throwable $e) {
            // لا شيء
        }
    }
};
