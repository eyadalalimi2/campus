<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::unprepared(<<<'SQL'
CREATE TABLE `asset_public_major` (
      `id` bigint(20) UNSIGNED NOT NULL,
      `asset_id` bigint(20) UNSIGNED NOT NULL,
      `public_major_id` bigint(20) UNSIGNED NOT NULL,
      `is_primary` tinyint(1) NOT NULL DEFAULT 0,
      `priority` int(11) NOT NULL DEFAULT 0,
      `created_at` timestamp NULL DEFAULT current_timestamp(),
      `primary_asset_id` bigint(20) UNSIGNED GENERATED ALWAYS AS (case when `is_primary` = 1 then `asset_id` else NULL end) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL);
    }

    public function down(): void {
        DB::unprepared("DROP TABLE IF EXISTS `asset_public_major`;");
    }
};
