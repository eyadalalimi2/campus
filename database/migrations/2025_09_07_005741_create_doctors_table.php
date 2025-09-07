<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('doctors', function (Blueprint $t) {
        $t->id();
        $t->string('name');
        $t->string('email')->nullable();
        $t->string('password')->nullable();
        $t->enum('type',['university','independent'])->default('university');
        $t->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
        $t->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete();
        $t->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();
        $t->string('degree')->nullable();
        $t->unsignedSmallInteger('degree_year')->nullable();
        $t->string('phone',30)->nullable();
        $t->string('photo_path')->nullable();
        $t->boolean('is_active')->default(true);
        $t->timestamps();
        $t->index('university_id');
        $t->index('college_id');
        $t->index('major_id');
    });
}
public function down(): void { Schema::dropIfExists('doctors'); }

};
