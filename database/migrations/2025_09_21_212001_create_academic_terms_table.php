<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `academic_terms` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `calendar_id` bigint(20) UNSIGNED NOT NULL,
      `name` enum('first','second','summer') NOT NULL,
      `starts_on` date NOT NULL,
      `ends_on` date NOT NULL,
      `is_active` tinyint(1) NOT NULL DEFAULT 1,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `academic_terms`;");
    }
};
