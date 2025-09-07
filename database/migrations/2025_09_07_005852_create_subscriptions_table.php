<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('subscriptions', function (Blueprint $t) {
        $t->id();
        $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $t->string('plan')->default('standard');
        $t->enum('status',['active','expired','canceled'])->default('active');
        $t->timestamp('started_at')->nullable();
        $t->timestamp('ends_at')->nullable();
        $t->boolean('auto_renew')->default(false);
        $t->integer('price_cents')->nullable();
        $t->char('currency',3)->default('YER');
        $t->timestamps();

        $t->index('status');
        $t->index('started_at');
        $t->index('ends_at');
    });
}
public function down(): void { Schema::dropIfExists('subscriptions'); }

};
