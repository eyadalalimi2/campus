<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `university_branches` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `university_id` bigint(20) UNSIGNED NOT NULL,
      `name` varchar(190) NOT NULL,
      `address` varchar(500) DEFAULT NULL,
      `phone` varchar(50) DEFAULT NULL,
      `email` varchar(190) DEFAULT NULL,
      `is_active` tinyint(1) NOT NULL DEFAULT 1,
      `created_at` timestamp NULL DEFAULT current_timestamp(),
      `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `university_branches`;");
    }
};
