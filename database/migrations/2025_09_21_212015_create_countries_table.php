<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `countries` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `name_ar` varchar(150) NOT NULL,
      `iso2` char(2) DEFAULT NULL,
      `phone_code` varchar(10) DEFAULT NULL,
      `currency_code` char(3) DEFAULT NULL,
      `is_active` tinyint(1) NOT NULL DEFAULT 1,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `countries`;");
    }
};
