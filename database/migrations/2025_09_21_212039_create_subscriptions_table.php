<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `subscriptions` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `user_id` bigint(20) UNSIGNED NOT NULL,
      `activation_code_id` bigint(20) UNSIGNED DEFAULT NULL,
      `plan_id` bigint(20) UNSIGNED NOT NULL,
      `status` enum('active','expired','canceled') NOT NULL DEFAULT 'active',
      `started_at` timestamp NULL DEFAULT NULL,
      `ends_at` timestamp NULL DEFAULT NULL,
      `auto_renew` tinyint(1) NOT NULL DEFAULT 0,
      `price_cents` int(11) DEFAULT NULL,
      `currency` char(3) NOT NULL DEFAULT 'YER',
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `subscriptions`;");
    }
};
