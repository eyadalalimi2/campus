<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `public_colleges` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `name` varchar(190) NOT NULL,
      `slug` varchar(190) DEFAULT NULL,
      `status` enum('active','archived') NOT NULL DEFAULT 'active',
      `created_at` timestamp NULL DEFAULT current_timestamp(),
      `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `public_colleges`;");
    }
};
