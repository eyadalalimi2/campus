<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('med_doctor_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('med_doctors')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('med_subjects')->onDelete('cascade');
            $table->unique(['doctor_id', 'subject_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('med_doctor_subject');
    }
};
