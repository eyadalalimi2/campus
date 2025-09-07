<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('disciplines', function (Blueprint $t) {
        $t->id();
        $t->string('name',150);
        $t->boolean('is_active')->default(true);
        $t->timestamps();
        $t->unique('name','uq_disc_name');
    });
}
public function down(): void { Schema::dropIfExists('disciplines'); }

};
