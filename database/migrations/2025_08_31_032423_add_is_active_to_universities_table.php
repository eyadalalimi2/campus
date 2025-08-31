<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            if (!Schema::hasColumn('universities', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('logo_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            if (Schema::hasColumn('universities', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
