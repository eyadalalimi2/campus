<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('devices', function (Blueprint $t) {
            $t->id();
            $t->foreignId('material_id')->constrained()->cascadeOnDelete();
            $t->string('name');              // جهاز/مهمة/معمل
            $t->string('code')->nullable();  // اختياري
            $t->text('description')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();

            $t->index(['material_id','is_active']);
        });
    }
    public function down(): void { Schema::dropIfExists('devices'); }
};
