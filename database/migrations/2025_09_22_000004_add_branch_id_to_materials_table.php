<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'branch_id')) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('university_id');
                $table->foreign('branch_id')
                    ->references('id')
                    ->on('university_branches')
                    ->cascadeOnDelete();
            }
        });
    }
    public function down(): void {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'branch_id')) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            }
        });
    }
};
