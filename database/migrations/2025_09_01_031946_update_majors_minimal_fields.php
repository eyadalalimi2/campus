<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('majors', function (Blueprint $table) {
            if (!Schema::hasColumn('majors', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('name');
            }
            if (Schema::hasColumn('majors', 'code')) {
                $table->dropColumn('code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('majors', function (Blueprint $table) {
            if (!Schema::hasColumn('majors', 'code')) {
                $table->string('code', 50)->nullable()->after('name');
            }
            if (Schema::hasColumn('majors', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
