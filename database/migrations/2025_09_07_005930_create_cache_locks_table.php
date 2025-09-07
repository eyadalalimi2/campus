<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('cache_locks', function (Blueprint $t) {
        $t->string('key')->primary();
        $t->string('owner');
        $t->integer('expiration');
    });
}
public function down(): void { Schema::dropIfExists('cache_locks'); }

};
