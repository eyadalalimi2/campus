<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('users', function (Blueprint $t) {
        $t->id();
        $t->string('student_number')->nullable()->unique();
        $t->string('name')->nullable();
        $t->string('email')->unique();
        $t->string('phone',20)->nullable();
        $t->string('country',100)->default('اليمن');
        $t->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
        $t->string('profile_photo_path')->nullable();

        $t->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
        $t->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete();
        $t->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();

        $t->tinyInteger('level')->nullable();
        $t->enum('gender',['male','female'])->nullable();
        $t->enum('status',['active','suspended','graduated'])->default('active');
        $t->timestamp('email_verified_at')->nullable();
        $t->string('password');
        $t->string('remember_token',100)->nullable();
        $t->timestamps();

        $t->index(['university_id','college_id','major_id'],'users_university_id_college_id_major_id_index');
        $t->index('country_id','idx_user_country');
    });
}
public function down(): void { Schema::dropIfExists('users'); }

};
