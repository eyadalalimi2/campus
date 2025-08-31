<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctors', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->enum('type', ['university','independent'])->default('university'); // جامعي أو مستقل
            // ارتباط جامعي (اختياري عند type = university)
            $t->foreignId('university_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('college_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('major_id')->nullable()->constrained()->nullOnDelete(); // تخصص أساسي واحد للجامعي
            // بيانات عامة
            $t->string('degree')->nullable();         // المؤهل الدراسي (ماجستير، دكتوراه...)
            $t->year('degree_year')->nullable();      // سنة المؤهل
            $t->string('phone', 30)->nullable();
            $t->string('photo_path')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('doctors'); }
};
