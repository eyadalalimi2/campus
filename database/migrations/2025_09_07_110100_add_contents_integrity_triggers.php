<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // احذف إن وُجدت مسبقًا لتفادي التعارض عند إعادة النشر
        DB::unprepared("DROP TRIGGER IF EXISTS trg_contents_bi;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_contents_bu;");

        // BEFORE INSERT
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_contents_bi
BEFORE INSERT ON contents
FOR EACH ROW
BEGIN
    -- 1) الجامعة إلزامية
    IF NEW.university_id IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: university_id is required for private content';
    END IF;

    -- 2) الكلية (إن وُجدت) يجب أن تتبع نفس الجامعة
    IF NEW.college_id IS NOT NULL AND NOT EXISTS (
        SELECT 1
        FROM colleges c
        WHERE c.id = NEW.college_id
          AND c.university_id = NEW.university_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: college_id does not belong to university_id';
    END IF;

    -- 3) التخصص (إن وُجد) يجب أن يتبع الكلية/الجامعة المحددة
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
END;
SQL);

        // BEFORE UPDATE
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_contents_bu
BEFORE UPDATE ON contents
FOR EACH ROW
BEGIN
    -- 1) الجامعة تظل إلزامية
    IF NEW.university_id IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: university_id is required for private content';
    END IF;

    -- 2) الكلية (إن وُجدت) يجب أن تتبع نفس الجامعة
    IF NEW.college_id IS NOT NULL AND NOT EXISTS (
        SELECT 1
        FROM colleges c
        WHERE c.id = NEW.college_id
          AND c.university_id = NEW.university_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'contents: college_id does not belong to university_id';
    END IF;

    -- 3) التخصص (إن وُجد) يجب أن يتبع الكلية/الجامعة المحددة
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
END;
SQL);
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_contents_bi;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_contents_bu;");
    }
};
