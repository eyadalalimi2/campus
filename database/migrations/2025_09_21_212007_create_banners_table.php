<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `banners` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `placement` enum('home') NOT NULL DEFAULT 'home',
      `title` varchar(150) DEFAULT NULL,
      `image_path` varchar(255) NOT NULL,
      `image_alt` varchar(150) DEFAULT NULL,
      `target_url` varchar(500) DEFAULT NULL,
      `open_external` tinyint(1) NOT NULL DEFAULT 1,
      `is_active` tinyint(1) NOT NULL DEFAULT 1,
      `starts_at` timestamp NULL DEFAULT NULL,
      `ends_at` timestamp NULL DEFAULT NULL,
      `sort_order` int(11) NOT NULL DEFAULT 0,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `banners`;");
    }
};
