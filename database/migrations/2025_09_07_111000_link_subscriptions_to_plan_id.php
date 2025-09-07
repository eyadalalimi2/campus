<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) إضافة plan_id مؤقتًا كـ NULLABLE
        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'plan_id')) {
                $table->unsignedBigInteger('plan_id')->nullable()->after('user_id');
            }
        });

        // 2) ترحيل البيانات: مطابقة subscriptions.plan مع plans.code
        DB::statement(<<<'SQL'
UPDATE subscriptions s
JOIN plans p ON p.code = s.plan
SET s.plan_id = p.id
WHERE s.plan_id IS NULL
SQL);

        // 3) تشديد القيد: plan_id NOT NULL (بعد التأكد أنه لا توجد NULLs)
        $missing = DB::table('subscriptions')->whereNull('plan_id')->count();
        if ($missing > 0) {
            // احتياط: اربط أي اشتراك بلا مطابقة بخطة "standard"
            $stdId = DB::table('plans')->where('code', 'standard')->value('id');
            if ($stdId) {
                DB::table('subscriptions')->whereNull('plan_id')->update(['plan_id' => $stdId]);
                $missing = DB::table('subscriptions')->whereNull('plan_id')->count();
            }
        }
        if ($missing > 0) {
            throw new \RuntimeException("Cannot enforce NOT NULL on subscriptions.plan_id: {$missing} NULL rows remain.");
        }

        // 4) إضافة FK وإجبار NOT NULL
        Schema::table('subscriptions', function (Blueprint $table) {
            // إجبار NOT NULL
            try {
                $table->unsignedBigInteger('plan_id')->nullable(false)->change();
            } catch (\Throwable $e) {
                DB::statement("ALTER TABLE subscriptions MODIFY plan_id BIGINT UNSIGNED NOT NULL;");
            }

            // إضافة FK (لا نحذف الخطة إن كانت مستخدمة)
            $table->foreign('plan_id')
                  ->references('id')->on('plans')
                  ->onDelete('restrict');
        });

        // (اختياري) يمكنك لاحقاً إزالة العمود النصي القديم `plan` عبر Migration منفصلة
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // إسقاط FK ثم إعادة plan_id إلى NULLABLE
            $table->dropForeign(['plan_id']);
        });

        try {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->unsignedBigInteger('plan_id')->nullable()->change();
            });
        } catch (\Throwable $e) {
            DB::statement("ALTER TABLE subscriptions MODIFY plan_id BIGINT UNSIGNED NULL;");
        }

        // لا نحذف العمود لتجنّب فقدان الارتباط—العودة ستكون يدوية عند الحاجة
    }
};
