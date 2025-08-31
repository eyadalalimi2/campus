<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contents', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->text('description')->nullable();

            // نوع المحتوى: ملف مرفوع، فيديو (يوتيوب)، رابط خارجي
            $t->enum('type', ['file','video','link'])->default('file');

            // مصدر المحتوى إن كان فيديو/رابط
            $t->string('source_url')->nullable();

            // مسار الملف إن كان type=file
            $t->string('file_path')->nullable();

            // النطاق: عام لكل الجامعات أو خاص بجامعة
            $t->enum('scope', ['global','university'])->default('university');

            // الربط بالمؤسسات (اختياري)
            $t->foreignId('university_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('college_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('major_id')->nullable()->constrained()->nullOnDelete();

            // ربط اختياري بدكتور
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();

            $t->boolean('is_active')->default(true);
            $t->timestamps();

            // فهارس عملية
            $t->index(['scope','type','is_active']);
            $t->index(['university_id','college_id','major_id']);
            $t->index(['doctor_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('contents');
    }
};
