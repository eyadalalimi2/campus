<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('material_term', function (Blueprint $t) {
        $t->id();
        $t->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
        $t->foreignId('term_id')->constrained('academic_terms')->cascadeOnDelete();
        $t->timestamp('created_at')->useCurrent();
        $t->unique(['material_id','term_id'],'uq_material_term');
        $t->index('term_id','fk_mterm_term');
    });
}
public function down(): void { Schema::dropIfExists('material_term'); }

};
