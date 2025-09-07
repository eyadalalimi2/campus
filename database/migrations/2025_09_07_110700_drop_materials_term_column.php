<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // حذف العمود القديم بعد نجاح الترحيل وتحديث الكود
        Schema::table('materials', function (Blueprint $table) {
            if (Schema::hasColumn('materials', 'term')) {
                $table->dropColumn('term');
            }
        });
    }

    public function down(): void
    {
        // إعادة العمود في حال أردت التراجع
        Schema::table('materials', function (Blueprint $table) {
            $table->enum('term', ['first','second','summer'])->nullable()->after('level');
        });
    }
};
