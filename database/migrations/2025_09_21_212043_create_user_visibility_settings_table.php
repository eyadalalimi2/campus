<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `user_visibility_settings` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `user_id` bigint(20) UNSIGNED NOT NULL,
      `show_name` tinyint(1) NOT NULL DEFAULT 1,
      `show_email` tinyint(1) NOT NULL DEFAULT 0,
      `show_phone` tinyint(1) NOT NULL DEFAULT 0,
      `show_university` tinyint(1) NOT NULL DEFAULT 1,
      `show_college` tinyint(1) NOT NULL DEFAULT 1,
      `show_major` tinyint(1) NOT NULL DEFAULT 1,
      `show_level` tinyint(1) NOT NULL DEFAULT 1,
      `show_gender` tinyint(1) NOT NULL DEFAULT 0,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `user_visibility_settings`;");
    }
};
