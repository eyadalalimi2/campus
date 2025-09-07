<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_acal_bi;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_acal_bu;");

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_acal_bi
BEFORE INSERT ON academic_calendars
FOR EACH ROW
BEGIN
    IF NEW.is_active = 1 AND EXISTS (
        SELECT 1 FROM academic_calendars ac
        WHERE ac.university_id = NEW.university_id
          AND ac.is_active = 1
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Only one active academic calendar is allowed per university';
    END IF;
END;
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_acal_bu
BEFORE UPDATE ON academic_calendars
FOR EACH ROW
BEGIN
    IF NEW.is_active = 1 AND EXISTS (
        SELECT 1 FROM academic_calendars ac
        WHERE ac.university_id = NEW.university_id
          AND ac.is_active = 1
          AND ac.id <> NEW.id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Only one active academic calendar is allowed per university';
    END IF;
END;
SQL);
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_acal_bi;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_acal_bu;");
    }
};
