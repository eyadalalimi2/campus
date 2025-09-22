<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `contents` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `title` varchar(255) NOT NULL,
      `description` text DEFAULT NULL,
      `type` enum('file','video','link') NOT NULL DEFAULT 'file',
      `source_url` varchar(255) DEFAULT NULL,
      `file_path` varchar(255) DEFAULT NULL,
      `university_id` bigint(20) UNSIGNED NOT NULL,
      `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
      `college_id` bigint(20) UNSIGNED DEFAULT NULL,
      `major_id` bigint(20) UNSIGNED DEFAULT NULL,
      `material_id` bigint(20) UNSIGNED DEFAULT NULL,
      `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
      `is_active` tinyint(1) NOT NULL DEFAULT 1,
      `status` enum('draft','in_review','published','archived') NOT NULL DEFAULT 'draft',
      `published_at` timestamp NULL DEFAULT NULL,
      `published_by_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
      `version` int(10) UNSIGNED NOT NULL DEFAULT 1,
      `changelog` text DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `contents`;");
    }
};
