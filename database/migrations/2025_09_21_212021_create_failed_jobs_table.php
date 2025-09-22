<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `failed_jobs` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `uuid` char(36) NOT NULL,
      `connection` text NOT NULL,
      `queue` text NOT NULL,
      `payload` longtext NOT NULL,
      `exception` longtext NOT NULL,
      `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `failed_jobs`;");
    }
};
