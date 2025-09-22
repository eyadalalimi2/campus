<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `users` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `student_number` varchar(255) DEFAULT NULL,
      `name` varchar(255) DEFAULT NULL,
      `email` varchar(255) NOT NULL,
      `phone` varchar(20) DEFAULT NULL,
      `country_id` bigint(20) UNSIGNED NOT NULL,
      `profile_photo_path` varchar(255) DEFAULT NULL,
      `university_id` bigint(20) UNSIGNED DEFAULT NULL,
      `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
      `college_id` bigint(20) UNSIGNED DEFAULT NULL,
      `major_id` bigint(20) UNSIGNED DEFAULT NULL,
      `level` tinyint(4) DEFAULT NULL,
      `gender` enum('male','female') DEFAULT NULL,
      `status` enum('active','suspended','graduated') NOT NULL DEFAULT 'active',
      `email_verified_at` timestamp NULL DEFAULT NULL,
      `password` varchar(255) NOT NULL,
      `remember_token` varchar(100) DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `users`;");
    }
};
