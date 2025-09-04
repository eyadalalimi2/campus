<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('plan')->default('standard');
            $table->enum('status', ['active','expired','canceled'])->default('active')->index('status');
            $table->timestamp('started_at')->nullable()->index('started_at');
            $table->timestamp('ends_at')->nullable()->index('ends_at');
            $table->boolean('auto_renew')->default(false);
            $table->integer('price_cents')->nullable();
            $table->string('currency', 3)->default('YER');
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();

            $table->index('user_id', 'subscriptions_user_id_foreign');
        });
    }

    public function down(): void {
        Schema::dropIfExists('subscriptions');
    }
};
