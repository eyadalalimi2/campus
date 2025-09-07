<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // لا تُكمل إن وُجد NULL (سدّ ثغرات)
        $nulls = DB::table('users')->whereNull('country_id')->count();
        if ($nulls > 0) {
            throw new \RuntimeException("users.country_id contains {$nulls} NULL rows.");
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_country_id_foreign');
        });

        // إعادة إضافة القيد بصيغة RESTRICT صريحة
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('country_id')
                  ->references('id')->on('countries')
                  ->onDelete('restrict'); // يمنع حذف دولة مستخدمة
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_country_id_foreign');
        });

        // إعادة القيد بصيغة SET NULL (الحالة السابقة المحتملة) — اختياري
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('country_id')
                  ->references('id')->on('countries')
                  ->onDelete('set null');
        });
    }
};
