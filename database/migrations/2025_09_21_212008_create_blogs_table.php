<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `blogs` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `title` varchar(255) NOT NULL,
      `slug` varchar(255) NOT NULL,
      `excerpt` varchar(500) DEFAULT NULL,
      `body` longtext DEFAULT NULL,
      `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
      `published_at` timestamp NULL DEFAULT NULL,
      `university_id` bigint(20) UNSIGNED DEFAULT NULL,
      `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
      `cover_image_path` varchar(255) DEFAULT NULL,
      `is_active` tinyint(1) NOT NULL DEFAULT 1,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `blogs`;");
    }
};
