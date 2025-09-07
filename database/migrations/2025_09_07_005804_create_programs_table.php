<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('programs', function (Blueprint $t) {
        $t->id();
        $t->foreignId('discipline_id')->constrained('disciplines')->cascadeOnDelete();
        $t->string('name',150);
        $t->boolean('is_active')->default(true);
        $t->timestamps();
        $t->unique(['discipline_id','name'],'uq_prog_disc_name');
        $t->index(['discipline_id','is_active']);
    });
}
public function down(): void { Schema::dropIfExists('programs'); }

};
