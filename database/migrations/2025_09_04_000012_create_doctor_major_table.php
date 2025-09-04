<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_major', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('major_id')->constrained('majors')->cascadeOnDelete();

            $table->unique(['doctor_id','major_id'], 'doctor_major_doctor_id_major_id_unique');
            $table->index('major_id', 'doctor_major_major_id_foreign');
        });
    }

    public function down(): void {
        Schema::dropIfExists('doctor_major');
    }
};

