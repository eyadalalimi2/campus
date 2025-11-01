<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // إزالة التكرارات: الإبقاء على أحدث سجل (أكبر id) لكل مستخدم
        // ملاحظة: هذا الاستعلام يفترض MySQL
        DB::statement('DELETE r1 FROM reviews r1 JOIN reviews r2 ON r1.user_id = r2.user_id AND r1.id < r2.id');

        // إضافة فهرس فريد لضمان مراجعة واحدة لكل مستخدم
        DB::statement('ALTER TABLE `reviews` ADD UNIQUE KEY `reviews_user_id_unique` (`user_id`)');
    }

    public function down(): void
    {
        // إزالة الفهرس الفريد
        DB::statement('ALTER TABLE `reviews` DROP INDEX `reviews_user_id_unique`');
    }
};
