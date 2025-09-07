<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void
    {
        // قبل الإدراج
        DB::unprepared(<<<'SQL'
        DROP TRIGGER IF EXISTS trg_assets_bi;
        CREATE TRIGGER trg_assets_bi
        BEFORE INSERT ON assets
        FOR EACH ROW
        BEGIN
            -- لو تم تمرير program_id يجب أن يطابق discipline_id
            IF NEW.program_id IS NOT NULL AND NOT EXISTS (
                SELECT 1
                FROM programs p
                WHERE p.id = NEW.program_id
                  AND NEW.discipline_id IS NOT NULL
                  AND p.discipline_id = NEW.discipline_id
            ) THEN
                SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'program_id does not belong to discipline_id';
            END IF;
        END
        SQL);

        // قبل التحديث
        DB::unprepared(<<<'SQL'
        DROP TRIGGER IF EXISTS trg_assets_bu;
        CREATE TRIGGER trg_assets_bu
        BEFORE UPDATE ON assets
        FOR EACH ROW
        BEGIN
            IF NEW.program_id IS NOT NULL AND NOT EXISTS (
                SELECT 1
                FROM programs p
                WHERE p.id = NEW.program_id
                  AND NEW.discipline_id IS NOT NULL
                  AND p.discipline_id = NEW.discipline_id
            ) THEN
                SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'program_id does not belong to discipline_id';
            END IF;
        END
        SQL);
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_assets_bi;');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_assets_bu;');
    }
};
