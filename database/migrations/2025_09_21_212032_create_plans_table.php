<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `plans` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `code` varchar(50) NOT NULL,
      `name` varchar(100) NOT NULL,
      `price_cents` int(11) DEFAULT NULL,
      `currency` char(3) NOT NULL DEFAULT 'YER',
      `billing_cycle` enum('monthly','yearly','one_time') NOT NULL DEFAULT 'monthly',
      `is_active` tinyint(1) NOT NULL DEFAULT 1,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `plans`;");
    }
};
