<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `activation_codes` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `batch_id` bigint(20) UNSIGNED DEFAULT NULL,
      `code` varchar(64) NOT NULL,
      `plan_id` bigint(20) UNSIGNED NOT NULL,
      `university_id` bigint(20) UNSIGNED DEFAULT NULL,
      `college_id` bigint(20) UNSIGNED DEFAULT NULL,
      `major_id` bigint(20) UNSIGNED DEFAULT NULL,
      `duration_days` int(10) UNSIGNED NOT NULL DEFAULT 365,
      `start_policy` enum('on_redeem','fixed_start') NOT NULL DEFAULT 'on_redeem',
      `starts_on` date DEFAULT NULL,
      `valid_from` timestamp NULL DEFAULT NULL,
      `valid_until` timestamp NULL DEFAULT NULL,
      `max_redemptions` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
      `redemptions_count` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
      `status` enum('active','redeemed','expired','disabled') NOT NULL DEFAULT 'active',
      `redeemed_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
      `redeemed_at` timestamp NULL DEFAULT NULL,
      `created_by_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `activation_codes`;");
    }
};
