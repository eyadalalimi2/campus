<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // تحقّق سريع: يجب ألا يبقى أي NULL قبل الشدّ
        $nulls = DB::table('users')->whereNull('country_id')->count();
        if ($nulls > 0) {
            throw new RuntimeException("Cannot enforce NOT NULL on users.country_id: $nulls NULL rows remain.");
        }

        Schema::table('users', function (Blueprint $table) {
            // 1) إسقاط القيد القديم (كان ON DELETE SET NULL)
            // اسم القيد في سكيمتك: users_country_id_foreign
            $table->dropForeign('users_country_id_foreign');
        });

        // 2) جعل العمود NOT NULL
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('country_id')->nullable(false)->change();
            });
        } catch (\Throwable $e) {
            // Fallback لبعض إصدارات MariaDB
            DB::statement("ALTER TABLE users MODIFY country_id BIGINT UNSIGNED NOT NULL;");
        }

        Schema::table('users', function (Blueprint $table) {
            // 3) إعادة إضافة القيد كـ RESTRICT/NO ACTION (لا يُسمح بحذف الدولة إن كانت مُستخدمة)
            $table->foreign('country_id')
                  ->references('id')->on('countries')
                  ->onDelete('restrict'); // أو اتركها بدون onDelete (NO ACTION)
        });

        // 4) إزالة العمود النصي القديم إذا كان موجودًا
        if (Schema::hasColumn('users', 'country')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('country');
            });
        }
    }

    public function down(): void
    {
        // إعادة العمود النصي (اختياري) وجعله Nullable
        if (!Schema::hasColumn('users', 'country')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('country', 100)->nullable()->after('phone');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            // إسقاط القيد الحالي (RESTRICT)
            $table->dropForeign('users_country_id_foreign');
        });

        // جعل العمود Nullable مرة أخرى
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('country_id')->nullable()->change();
            });
        } catch (\Throwable $e) {
            DB::statement("ALTER TABLE users MODIFY country_id BIGINT UNSIGNED NULL;");
        }

        Schema::table('users', function (Blueprint $table) {
            // إعادة القيد القديم: ON DELETE SET NULL
            $table->foreign('country_id')
                  ->references('id')->on('countries')
                  ->onDelete('set null');
        });
    }
};
