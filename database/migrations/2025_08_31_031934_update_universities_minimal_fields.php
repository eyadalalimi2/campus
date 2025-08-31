<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            // أعمدة أساسية مع التأكد من وجودها
            if (!Schema::hasColumn('universities', 'address')) {
                $table->string('address', 500)->after('name');
            }
            if (!Schema::hasColumn('universities', 'phone')) {
                $table->string('phone', 50)->nullable()->after('address');
            }
            if (!Schema::hasColumn('universities', 'logo_path')) {
                $table->string('logo_path', 255)->nullable()->after('phone');
            } else {
                // تأكد أنها قابلة للإلغاء (لا يتطلب DBAL في MySQL إذا لم تغيّر النوع)
                // إذا احتجت تعديل النوع/الخواص استخدم doctrine/dbal
                // $table->string('logo_path', 255)->nullable()->change();
            }

            // حذف الأعمدة غير المستخدمة
            $drop = [];
            foreach (['slug','code','favicon_path','primary_color','secondary_color','is_active'] as $col) {
                if (Schema::hasColumn('universities', $col)) {
                    $drop[] = $col;
                }
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });

        // فهرس اختياري لتحسين البحث بالاسم
        if (!Schema::hasColumn('universities', 'name')) {
            // احتياطيًا، يفترض موجود بالفعل
        } else {
            Schema::table('universities', function (Blueprint $table) {
                $table->index('name', 'universities_name_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::table('universities', function (Blueprint $table) {
            // إعادة الأعمدة المحذوفة كخيارات رجوع (Nullable)
            if (!Schema::hasColumn('universities', 'slug')) {
                $table->string('slug', 255)->nullable()->after('name');
            }
            if (!Schema::hasColumn('universities', 'code')) {
                $table->string('code', 50)->nullable()->after('slug');
            }
            if (!Schema::hasColumn('universities', 'favicon_path')) {
                $table->string('favicon_path', 255)->nullable()->after('logo_path');
            }
            if (!Schema::hasColumn('universities', 'primary_color')) {
                $table->string('primary_color', 20)->nullable()->after('favicon_path');
            }
            if (!Schema::hasColumn('universities', 'secondary_color')) {
                $table->string('secondary_color', 20)->nullable()->after('primary_color');
            }
            if (!Schema::hasColumn('universities', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('secondary_color');
            }

            // حذف الأعمدة المضافة حديثًا في الـ up()
            if (Schema::hasColumn('universities', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('universities', 'phone')) {
                $table->dropColumn('phone');
            }
            // لا نحذف logo_path في الرجوع لأنه كان موجودًا مسبقًا في أغلب الحالات
        });

        // إزالة الفهرس الاختياري
        Schema::table('universities', function (Blueprint $table) {
            try { $table->dropIndex('universities_name_idx'); } catch (\Throwable $e) {}
        });
    }
};
