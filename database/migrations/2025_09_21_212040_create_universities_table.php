<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `universities` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `name` varchar(255) NOT NULL,
      `address` varchar(500) NOT NULL,
      `country_id` bigint(20) UNSIGNED DEFAULT NULL,
      `phone` varchar(50) DEFAULT NULL,
      `logo_path` varchar(255) DEFAULT NULL,
      `primary_color` varchar(20) DEFAULT NULL,
      `secondary_color` varchar(20) DEFAULT NULL,
      `theme_mode` enum('auto','light','dark') NOT NULL DEFAULT 'auto',
      `is_active` tinyint(1) NOT NULL DEFAULT 1,
      `use_default_theme` tinyint(1) NOT NULL DEFAULT 0,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `universities`;");
    }
};
