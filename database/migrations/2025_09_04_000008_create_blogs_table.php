<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique('slug');
            $table->string('excerpt', 500)->nullable();
            $table->longText('body')->nullable();
            $table->enum('status', ['draft','published','archived'])->default('draft')->index('status');
            $table->timestamp('published_at')->nullable()->index('published_at');
            $table->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->string('cover_image_path')->nullable();
            $table->boolean('is_active')->default(true);
            // مطابق للـ dump: defaults current + on update current
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->nullable();

            $table->index(['university_id','doctor_id'], 'university_id');
            $table->index('doctor_id', 'blogs_doctor_id_foreign');
        });
    }

    public function down(): void {
        Schema::dropIfExists('blogs');
    }
};
