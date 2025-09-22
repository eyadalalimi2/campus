<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `student_requests` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `user_id` bigint(20) UNSIGNED NOT NULL,
      `category` enum('general','material','account','technical','other') NOT NULL DEFAULT 'general',
      `title` varchar(255) NOT NULL,
      `body` text DEFAULT NULL,
      `admin_notes` text DEFAULT NULL,
      `priority` enum('low','normal','high') NOT NULL DEFAULT 'normal',
      `status` enum('open','in_progress','resolved','rejected','closed') NOT NULL DEFAULT 'open',
      `assigned_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
      `closed_at` timestamp NULL DEFAULT NULL,
      `attachment_path` varchar(255) DEFAULT NULL,
      `material_id` bigint(20) UNSIGNED DEFAULT NULL,
      `content_id` bigint(20) UNSIGNED DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `student_requests`;");
    }
};
