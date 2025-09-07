<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 50)->unique(); // مثال: standard, premium
            $table->string('name', 100);
            $table->integer('price_cents')->nullable(); // السعر بالـ YER أو أي عملة أخرى
            $table->char('currency', 3)->default('YER');
            $table->enum('billing_cycle', ['monthly','yearly','one_time'])->default('monthly');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'billing_cycle'], 'idx_plans_active_cycle');
        });

        Schema::create('plan_features', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('plan_id');
            $table->string('feature_key', 100);   // مثال: max_devices, offline_access
            $table->text('feature_value')->nullable(); // نص/JSON صغير
            $table->timestamps();

            $table->unique(['plan_id', 'feature_key'], 'uq_plan_feature_key');
            $table->foreign('plan_id')
                  ->references('id')->on('plans')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('plan_features', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
        });
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('plans');
    }
};
