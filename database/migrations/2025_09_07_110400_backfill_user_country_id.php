<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) تأكيد وجود صف لليمن في جدول الدول (اسم عربي "اليمن")
        // إذا لم يوجد، أنشئه مبدئيًا (يمكنك لاحقًا تحديث الأكواد حسب بياناتك الرسمية)
        DB::statement("
            INSERT INTO countries (name_ar, iso2, phone_code, currency_code, is_active, created_at, updated_at)
            SELECT 'اليمن', 'YE', '+967', 'YER', 1, NOW(), NOW()
            WHERE NOT EXISTS (
                SELECT 1 FROM countries WHERE name_ar = 'اليمن'
            );
        ");

        // 2) الحصول على معرّف اليمن
        $yemenId = DB::table('countries')->where('name_ar', 'اليمن')->value('id');

        // 3) مطابقة مباشرة بالاسم العربي
        DB::statement("
            UPDATE users u
            JOIN countries c ON c.name_ar = u.country
            SET u.country_id = c.id
            WHERE u.country IS NOT NULL AND u.country <> '' AND u.country_id IS NULL
        ");

        // 4) محاولات مطابقة إضافية (اختياري): بالاختصارات إن كنت تخزّنها نصيًا في users.country
        // - ISO2
        DB::statement("
            UPDATE users u
            JOIN countries c ON c.iso2 = u.country
            SET u.country_id = c.id
            WHERE u.country_id IS NULL
        ");

        // 5) ملء ما تبقّى باليمن كافتراضي (وفق حالتك الحالية حيث default كان 'اليمن')
        if ($yemenId) {
            DB::statement("
                UPDATE users
                SET country_id = {$yemenId}
                WHERE country_id IS NULL
            ");
        }
    }

    public function down(): void
    {
        // لا نعيد تفريغ country_id حتى لا نكسر النزاهة
        // (اختياري) يمكنك إعادة users.country_id إلى NULL إذا لزم
    }
};
