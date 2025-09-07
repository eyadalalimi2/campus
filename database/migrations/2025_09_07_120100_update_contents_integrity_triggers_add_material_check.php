<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // إسقاط القوادح القديمة لإعادة تعريفها مع فحص material_id
        DB::unprepared("DROP TRIGGER IF EXISTS trg_contents_bi;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_contents_bu;");

        // BEFORE INSERT
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_contents_bi
BEFORE INSERT ON contents
FOR EACH ROW
BEGIN
    -- (A) الجامعة إلزامية
    IF NEW.university_id IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: university_id is required for private content';
    END IF;

    -- (B) الكلية (إن وُجدت) يجب أن تتبع نفس الجامعة
    IF NEW.college_id IS NOT NULL AND NOT EXISTS (
        SELECT 1
        FROM colleges c
        WHERE c.id = NEW.college_id
          AND c.university_id = NEW.university_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: college_id does not belong to university_id';
    END IF;

    -- (C) التخصص (إن وُجد) يجب أن يتبع الكلية/الجامعة المحددة
    IF NEW.major_id IS NOT NULL AND NOT EXISTS (
        SELECT 1
        FROM majors m
        JOIN colleges c ON c.id = m.college_id
        WHERE m.id = NEW.major_id
          AND c.university_id = NEW.university_id
          AND (NEW.college_id IS NULL OR m.college_id = NEW.college_id)
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: major_id does not belong to provided college/university';
    END IF;

    -- (D) اتساق material_id مع الهرم المؤسسي أو السماح بالـglobal
    IF NEW.material_id IS NOT NULL AND NOT EXISTS (
        SELECT 1
        FROM materials mm
        WHERE mm.id = NEW.material_id
          AND (
                -- مواد عامة مسموحة للجميع
                mm.scope = 'global'
                OR
                -- مواد مؤسسية: يجب أن تتبع نفس الجامعة، مع تطابق الكلية/التخصص إن حددهما المحتوى
                (
                  mm.scope = 'university'
                  AND mm.university_id = NEW.university_id
                  AND (NEW.college_id IS NULL OR mm.college_id = NEW.college_id OR mm.college_id IS NULL)
                  AND (NEW.major_id   IS NULL OR mm.major_id   = NEW.major_id   OR mm.major_id   IS NULL)
                )
              )
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: material_id is not consistent with university/college/major (or not global)';
    END IF;
END;
SQL);

        // BEFORE UPDATE
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_contents_bu
BEFORE UPDATE ON contents
FOR EACH ROW
BEGIN
    -- (A) الجامعة تظل إلزامية
    IF NEW.university_id IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: university_id is required for private content';
    END IF;

    -- (B) الكلية (إن وُجدت) يجب أن تتبع نفس الجامعة
    IF NEW.college_id IS NOT NULL AND NOT EXISTS (
        SELECT 1
        FROM colleges c
        WHERE c.id = NEW.college_id
          AND c.university_id = NEW.university_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: college_id does not belong to university_id';
    END IF;

    -- (C) التخصص (إن وُجد) يجب أن يتبع الكلية/الجامعة المحددة
    IF NEW.major_id IS NOT NULL AND NOT EXISTS (
        SELECT 1
        FROM majors m
        JOIN colleges c ON c.id = m.college_id
        WHERE m.id = NEW.major_id
          AND c.university_id = NEW.university_id
          AND (NEW.college_id IS NULL OR m.college_id = NEW.college_id)
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: major_id does not belong to provided college/university';
    END IF;

    -- (D) اتساق material_id مع الهرم المؤسسي أو السماح بالـglobal
    IF NEW.material_id IS NOT NULL AND NOT EXISTS (
        SELECT 1
        FROM materials mm
        WHERE mm.id = NEW.material_id
          AND (
                mm.scope = 'global'
                OR (
                    mm.scope = 'university'
                    AND mm.university_id = NEW.university_id
                    AND (NEW.college_id IS NULL OR mm.college_id = NEW.college_id OR mm.college_id IS NULL)
                    AND (NEW.major_id   IS NULL OR mm.major_id   = NEW.major_id   OR mm.major_id   IS NULL)
                )
              )
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: material_id is not consistent with university/college/major (or not global)';
    END IF;
END;
SQL);
    }

    public function down(): void
    {
        // نرجع لتعريف القوادح السابق بدون فحص material_id
        DB::unprepared("DROP TRIGGER IF EXISTS trg_contents_bi;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_contents_bu;");
    }
};
