<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `notifications` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `user_id` bigint(20) UNSIGNED NOT NULL,
      `title` varchar(255) NOT NULL,
      `body` text DEFAULT NULL,
      `target_type` varchar(50) DEFAULT NULL,
      `target_id` int(11) DEFAULT NULL,
      `type` enum('content_created','content_updated','content_deleted','asset_created','asset_updated','asset_deleted','system','other') NOT NULL DEFAULT 'system',
      `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
      `read_at` timestamp NULL DEFAULT NULL,
      `content_id` bigint(20) UNSIGNED DEFAULT NULL,
      `asset_id` bigint(20) UNSIGNED DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `notifications`;");
    }
};
