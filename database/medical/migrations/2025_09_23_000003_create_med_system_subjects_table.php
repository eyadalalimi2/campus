<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_system_subjects', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('system_id'); // med_systems.id
            $t->unsignedBigInteger('subject_id'); // med_subjects.id
            $t->tinyInteger('semester_hint')->nullable();
            $t->tinyInteger('level')->nullable();
            $t->timestamps();
            $t->unique(['system_id','subject_id']);
            $t->index(['subject_id','system_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('med_system_subjects'); }
};