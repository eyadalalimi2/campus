<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
            Schema::table('colleges', function (Blueprint $table) {
                if (!Schema::hasColumn('colleges', 'university_id')) {
                    $table->unsignedBigInteger('university_id')->after('id')->nullable();
                }
            });

            // ضبط جميع القيم الحالية إلى 1
            DB::table('colleges')->update(['university_id' => 1]);

            Schema::table('colleges', function (Blueprint $table) {
                $table->unsignedBigInteger('university_id')->change();
                $table->foreign('university_id')->references('id')->on('universities')->cascadeOnDelete();
            });
    }
    public function down(): void {
        Schema::table('colleges', function (Blueprint $table) {
            if (Schema::hasColumn('colleges', 'university_id')) {
                $table->dropForeign(['university_id']);
                $table->dropColumn('university_id');
            }
        });
    }
};
