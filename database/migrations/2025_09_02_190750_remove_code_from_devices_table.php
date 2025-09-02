<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * تنفيذ عملية الحذف.
     */
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (Schema::hasColumn('devices', 'code')) {
                $table->dropColumn('code');
            }
        });
    }

    /**
     * التراجع عن الحذف (لعمل rollback).
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('code')->nullable()->after('name');
        });
    }
};
