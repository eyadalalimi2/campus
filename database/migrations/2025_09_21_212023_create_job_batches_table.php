<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `job_batches` (
      `id` varchar(255) NOT NULL,
      `name` varchar(255) NOT NULL,
      `total_jobs` int(11) NOT NULL,
      `pending_jobs` int(11) NOT NULL,
      `failed_jobs` int(11) NOT NULL,
      `failed_job_ids` longtext NOT NULL,
      `options` mediumtext DEFAULT NULL,
      `cancelled_at` int(11) DEFAULT NULL,
      `created_at` int(11) NOT NULL,
      `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `job_batches`;");
    }
};
