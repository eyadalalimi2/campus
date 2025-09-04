<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->enum('type', ['university','independent'])->default('university');
            $table->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
            $table->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete();
            $table->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();
            $table->string('degree')->nullable();
            // لا يوجد نوع year() في Schema Builder؛ نستخدم smallInteger
            $table->unsignedSmallInteger('degree_year')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('university_id', 'doctors_university_id_foreign');
            $table->index('college_id', 'doctors_college_id_foreign');
            $table->index('major_id', 'doctors_major_id_foreign');
        });
    }

    public function down(): void {
        Schema::dropIfExists('doctors');
    }
};
