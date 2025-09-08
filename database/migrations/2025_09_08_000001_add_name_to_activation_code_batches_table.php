<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activation_code_batches', function (Blueprint $table) {
            if (!Schema::hasColumn('activation_code_batches', 'name')) {
                // لو كان لديك title سابقًا وتريد تحويله إلى name، بدّل السطر التالي بـ changeColumn في SQL
                $table->string('name', 150)->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('activation_code_batches', function (Blueprint $table) {
            if (Schema::hasColumn('activation_code_batches', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
