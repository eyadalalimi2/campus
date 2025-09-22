<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('materials', function (Blueprint $table) {
            if (!Schema::hasColumn('materials', 'scope')) {
                $table->string('scope', 20)->default('university')->after('name'); // 'global' or 'university'
            }
            if (!Schema::hasColumn('materials', 'university_id')) {
                $table->unsignedBigInteger('university_id')->nullable()->after('scope');
                $table->foreign('university_id')->references('id')->on('universities')->nullOnDelete();
            }
            if (!Schema::hasColumn('materials', 'college_id')) {
                $table->unsignedBigInteger('college_id')->nullable()->after('university_id');
                $table->foreign('college_id')->references('id')->on('colleges')->nullOnDelete();
            }
            if (!Schema::hasColumn('materials', 'major_id')) {
                $table->unsignedBigInteger('major_id')->nullable()->after('college_id');
                $table->foreign('major_id')->references('id')->on('majors')->nullOnDelete();
            }
            if (!Schema::hasColumn('materials', 'level')) {
                $table->unsignedTinyInteger('level')->nullable()->after('major_id');
            }
        });
    }

    public function down(): void {
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'major_id')) {
                $table->dropForeign(['major_id']);   $table->dropColumn('major_id');
            }
            if (Schema::hasColumn('materials', 'college_id')) {
                $table->dropForeign(['college_id']); $table->dropColumn('college_id');
            }
            if (Schema::hasColumn('materials', 'university_id')) {
                $table->dropForeign(['university_id']); $table->dropColumn('university_id');
            }
            if (Schema::hasColumn('materials', 'level')) {
                $table->dropColumn('level');
            }
            if (Schema::hasColumn('materials', 'scope')) {
                $table->dropColumn('scope');
            }
        });
    }
};
