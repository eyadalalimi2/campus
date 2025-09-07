<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $violations = DB::select(<<<'SQL'
SELECT university_id, COUNT(*) AS cnt
FROM academic_calendars
WHERE is_active = 1
GROUP BY university_id
HAVING cnt > 1
SQL);
        if (!empty($violations)) {
            throw new \RuntimeException('Found universities with multiple active calendars. Fix data before proceeding.');
        }
    }

    public function down(): void
    {
        // لا شيء
    }
};
