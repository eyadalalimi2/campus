<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('blogs', function (Blueprint $t) {
        $t->id();
        $t->string('title');
        $t->string('slug')->unique();
        $t->string('excerpt',500)->nullable();
        $t->longText('body')->nullable();
        $t->enum('status',['draft','published','archived'])->default('draft');
        $t->timestamp('published_at')->nullable();
        $t->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
        $t->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
        $t->string('cover_image_path')->nullable();
        $t->boolean('is_active')->default(true);
        $t->timestamps();
        $t->index(['status']);
        $t->index(['published_at']);
        $t->index(['university_id','doctor_id']);
    });
}
public function down(): void { Schema::dropIfExists('blogs'); }

};
