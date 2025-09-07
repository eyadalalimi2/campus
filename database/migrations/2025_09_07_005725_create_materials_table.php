<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('materials', function (Blueprint $t) {
        $t->id();
        $t->string('name');
        $t->enum('scope',['global','university'])->default('university');
        $t->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
        $t->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete();
        $t->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();
        $t->unsignedTinyInteger('level')->nullable();
        $t->enum('term',['first','second','summer'])->nullable();
        $t->boolean('is_active')->default(true);
        $t->timestamps();
        $t->index(['scope','university_id','college_id','major_id'],'materials_scope_university_id_college_id_major_id_index');
        $t->index(['level','term'],'materials_level_term_index');
    });
}
public function down(): void { Schema::dropIfExists('materials'); }

};
