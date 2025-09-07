<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('devices', function (Blueprint $t) {
        $t->id();
        $t->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
        $t->string('name');
        $t->text('description')->nullable();
        $t->boolean('is_active')->default(true);
        $t->timestamps();
        $t->index(['material_id','is_active'],'devices_material_id_is_active_index');
    });
}
public function down(): void { Schema::dropIfExists('devices'); }

};
