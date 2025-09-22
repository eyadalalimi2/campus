<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `complaints` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `user_id` bigint(20) UNSIGNED NOT NULL,
      `type` enum('content','asset','user','bug','abuse','other') NOT NULL DEFAULT 'other',
      `subject` varchar(255) NOT NULL,
      `body` text DEFAULT NULL,
      `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'low',
      `status` enum('open','triaged','in_progress','resolved','rejected','closed') NOT NULL DEFAULT 'open',
      `target_type` varchar(50) DEFAULT NULL,
      `target_id` bigint(20) UNSIGNED DEFAULT NULL,
      `assigned_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
      `closed_at` timestamp NULL DEFAULT NULL,
      `attachment_path` varchar(255) DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `complaints`;");
    }
};
