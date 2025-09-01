<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            if (!Schema::hasColumn('universities', 'primary_color')) {
                $table->string('primary_color', 20)->nullable()->after('logo_path');
            }
            if (!Schema::hasColumn('universities', 'secondary_color')) {
                $table->string('secondary_color', 20)->nullable()->after('primary_color');
            }
            if (!Schema::hasColumn('universities', 'theme_mode')) {
                $table->enum('theme_mode', ['auto','light','dark'])->default('auto')->after('secondary_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            if (Schema::hasColumn('universities', 'theme_mode')) {
                $table->dropColumn('theme_mode');
            }
            if (Schema::hasColumn('universities', 'secondary_color')) {
                $table->dropColumn('secondary_color');
            }
            if (Schema::hasColumn('universities', 'primary_color')) {
                $table->dropColumn('primary_color');
            }
        });
    }
};
