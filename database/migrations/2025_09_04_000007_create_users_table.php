<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('student_number')->nullable()->unique('users_student_number_unique');
            $table->string('name')->nullable();
            $table->string('email')->unique('users_email_unique');
            $table->string('phone', 20)->nullable();
            $table->string('country', 100)->default('اليمن');
            $table->string('profile_photo_path')->nullable();
            $table->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
            $table->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete();
            $table->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();
            $table->tinyInteger('level')->nullable();
            $table->enum('gender', ['male','female'])->nullable();
            $table->enum('status', ['active','suspended','graduated'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->index(['university_id','college_id','major_id'], 'users_university_id_college_id_major_id_index');
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};

