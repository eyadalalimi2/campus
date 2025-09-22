<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `assets` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `scope` enum('PUBLIC') NOT NULL DEFAULT 'PUBLIC',
      `material_id` bigint(20) UNSIGNED DEFAULT NULL,
      `device_id` bigint(20) UNSIGNED DEFAULT NULL,
      `doctor_id` bigint(20) UNSIGNED DEFAULT NULL,
      `discipline_id` bigint(20) UNSIGNED DEFAULT NULL,
      `program_id` bigint(20) UNSIGNED DEFAULT NULL,
      `category` enum('youtube','file','reference','question_bank','curriculum','book') NOT NULL,
      `title` varchar(255) NOT NULL,
      `description` text DEFAULT NULL,
      `status` enum('draft','in_review','published','archived') NOT NULL DEFAULT 'draft',
      `published_at` timestamp NULL DEFAULT NULL,
      `published_by_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
      `video_url` varchar(255) DEFAULT NULL,
      `file_path` varchar(255) DEFAULT NULL,
      `external_url` varchar(255) DEFAULT NULL,
      `is_active` tinyint(1) NOT NULL DEFAULT 1,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `assets`;");
    }
};
