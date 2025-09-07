<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('blogs', function (Blueprint $table) {
            $table->softDeletes();  // يضيف عمود deleted_at قابلاً للفراغ
        });
    }

    public function down(): void {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
