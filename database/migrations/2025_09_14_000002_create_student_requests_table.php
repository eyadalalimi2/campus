<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_requests', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id'); // الطالب مقدم الطلب
            $table->enum('category', [
                'general',        // عام
                'material',       // طلب مادة/مرجع
                'account',        // مشكلة حساب/تفعيل
                'technical',      // تقني
                'other'
            ])->default('general');

            $table->string('title', 255);
            $table->text('body')->nullable();

            $table->enum('priority', ['low','normal','high'])->default('normal');
            $table->enum('status', ['open','in_progress','resolved','rejected','closed'])->default('open');

            // تعيين/متابعة من قبل الإدارة
            $table->unsignedBigInteger('assigned_admin_id')->nullable();
            $table->timestamp('closed_at')->nullable();

            // مرفق اختياري واحد (يمكن لاحقاً عمل جدول مرفقات)
            $table->string('attachment_path', 255)->nullable();

            // سياق اختياري يربط الطلب بمواد/محتوى
            $table->unsignedBigInteger('material_id')->nullable();
            $table->unsignedBigInteger('content_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id','status','priority']);
            $table->index(['category','created_at']);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('assigned_admin_id')->references('id')->on('admins')->nullOnDelete();
            $table->foreign('material_id')->references('id')->on('materials')->nullOnDelete();
            $table->foreign('content_id')->references('id')->on('contents')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_requests');
    }
};
