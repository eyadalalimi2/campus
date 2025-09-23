<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_doctor_subjects', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('doctor_id');   // med_doctors.id
            $t->unsignedBigInteger('subject_id');  // med_subjects.id
            $t->tinyInteger('priority')->default(5);
            $t->boolean('featured')->default(false);
            $t->timestamps();
            $t->unique(['doctor_id','subject_id']);
            $t->index(['subject_id','doctor_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('med_doctor_subjects'); }
};