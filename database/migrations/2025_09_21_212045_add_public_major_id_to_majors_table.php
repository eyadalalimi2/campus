<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('majors', function (Blueprint $table) {
            // إضافة العمود بعد عمود college_id (اختياري)
            $table->unsignedBigInteger('public_major_id')->nullable()->after('college_id');

            // إنشاء المفتاح الأجنبي
            $table->foreign('public_major_id')
                  ->references('id')
                  ->on('public_majors')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('majors', function (Blueprint $table) {
            $table->dropForeign(['public_major_id']);
            $table->dropColumn('public_major_id');
        });
    }
};
