<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('content_task')) {
            Schema::drop('content_task');
        }
        if (Schema::hasTable('tasks')) {
            Schema::drop('tasks');
        }
    }

    public function down(): void
    {
        // اختياري: يمكنك تركها فارغة أو إعادة إنشاء الجداول لو رغبت لاحقًا
    }
};
