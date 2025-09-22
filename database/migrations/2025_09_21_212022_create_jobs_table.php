<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `jobs` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `queue` varchar(255) NOT NULL,
      `payload` longtext NOT NULL,
      `attempts` tinyint(3) UNSIGNED NOT NULL,
      `reserved_at` int(10) UNSIGNED DEFAULT NULL,
      `available_at` int(10) UNSIGNED NOT NULL,
      `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `jobs`;");
    }
};
