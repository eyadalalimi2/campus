<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_doctor_subject_systems', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('doctor_subject_id'); // med_doctor_subjects.id
            $t->unsignedBigInteger('system_id');         // med_systems.id
            $t->string('playlist_id',100)->nullable();
            $t->string('tag',100)->nullable(); // system:CARDIO
            $t->timestamps();
            $t->unique(['doctor_subject_id','system_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('med_doctor_subject_systems'); }
};