<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctor_major', function (Blueprint $t) {
            $t->id();
            $t->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $t->foreignId('major_id')->constrained()->cascadeOnDelete();
            $t->unique(['doctor_id','major_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('doctor_major'); }
};
