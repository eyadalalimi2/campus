<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `major_program` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `major_id` bigint(20) UNSIGNED NOT NULL,
      `program_id` bigint(20) UNSIGNED NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `major_program`;");
    }
};
