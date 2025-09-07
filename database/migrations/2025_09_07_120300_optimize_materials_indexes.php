<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // إسقاط الفهرس القديم إن وُجد (قد يفشل الاسم على بعض البيئات؛ نلتقط ونكمل)
        try {
            DB::statement("ALTER TABLE materials DROP INDEX materials_level_term_index;");
        } catch (\Throwable $e) {
            // تجاهل لو غير موجود
        }

        // إنشاء فهرس واضح على level
        Schema::table('materials', function (Blueprint $table) {
            $table->index('level', 'idx_materials_level');
        });
    }

    public function down(): void
    {
        // إسقاط الفهرس الجديد
        Schema::table('materials', function (Blueprint $table) {
            $table->dropIndex('idx_materials_level');
        });

        // (اختياري) إعادة الفهرس القديم بالاسم السابق
        try {
            DB::statement("ALTER TABLE materials ADD INDEX materials_level_term_index (level);");
        } catch (\Throwable $e) {}
    }
};
