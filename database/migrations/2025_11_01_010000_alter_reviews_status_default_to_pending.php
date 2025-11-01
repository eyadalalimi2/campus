<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Change default to 'pending' for new rows (use raw SQL to avoid requiring doctrine/dbal)
        DB::statement("ALTER TABLE `reviews` MODIFY `status` VARCHAR(20) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Revert default back to 'approved'
        DB::statement("ALTER TABLE `reviews` MODIFY `status` VARCHAR(20) NOT NULL DEFAULT 'approved'");
    }
};
