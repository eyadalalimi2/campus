<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // نحاول ربط كل material.term موجود بترم مطابق ضمن تقويم الجامعة الفعّال
        // ملحوظة: لن نعالج المواد "العالمية" بدون university_id (سيتم تجاهلها).
        // يمنع التكرار عبر NOT EXISTS احترامًا للقيْد UNIQUE (material_id, term_id).

        DB::statement(<<<'SQL'
INSERT INTO material_term (material_id, term_id, created_at)
SELECT
    m.id                  AS material_id,
    t.id                  AS term_id,
    NOW()                 AS created_at
FROM materials m
JOIN universities u
    ON u.id = m.university_id
JOIN academic_calendars cal
    ON cal.university_id = u.id
   AND cal.is_active = 1
JOIN academic_terms t
    ON t.calendar_id = cal.id
   AND t.is_active = 1
   AND t.name = m.term  -- تطابق enum: first/second/summer
WHERE
    m.term IS NOT NULL
    AND m.university_id IS NOT NULL
    AND NOT EXISTS (
        SELECT 1
        FROM material_term mt
        WHERE mt.material_id = m.id
          AND mt.term_id = t.id
    );
SQL);

        // اختيارية (تقارير سريعة):
        // يمكنك فحص عدد المواد التي لم تُرحّل (لا تملك university_id أو لا يوجد لها تقويم فعّال/ترم مطابق)
        // عبر استعلام يدوي لاحقًا عند الحاجة.
    }

    public function down(): void
    {
        // التراجع: إزالة أي روابط أنشأناها في هذه الهجرة تحديدًا.
        // لا يمكن تمييزها بدقة زمنية سوى عبر created_at ≈ وقت التنفيذ.
        // لذلك لن نحذف شيئًا في down() لتجنّب إزالة بيانات ربما أضيفت لاحقًا يدويًا.
    }
};
