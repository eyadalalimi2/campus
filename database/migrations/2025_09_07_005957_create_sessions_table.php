<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('sessions', function (Blueprint $t) {
        $t->string('id')->primary();
        $t->foreignId('user_id')->nullable()->constrained('users');
        $t->string('ip_address',45)->nullable();
        $t->text('user_agent')->nullable();
        $t->longText('payload');
        $t->integer('last_activity');
        $t->index('user_id');
        $t->index('last_activity');
    });
}
public function down(): void { Schema::dropIfExists('sessions'); }

};
