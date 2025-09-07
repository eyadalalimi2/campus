<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('doctor_major', function (Blueprint $t) {
        $t->id();
        $t->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
        $t->foreignId('major_id')->constrained('majors')->cascadeOnDelete();
        $t->unique(['doctor_id','major_id'],'doctor_major_doctor_id_major_id_unique');
    });
}
public function down(): void { Schema::dropIfExists('doctor_major'); }

};
