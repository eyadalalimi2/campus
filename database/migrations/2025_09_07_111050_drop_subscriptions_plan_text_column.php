<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('subscriptions', 'plan')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('plan');
            });
        }
    }

    public function down(): void
    {
        // إعادة العمود النصي عند التراجع
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('plan', 255)->default('standard')->after('user_id');
        });
    }
};
