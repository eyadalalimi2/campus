<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id'); // مقدم البلاغ
            $table->enum('type', ['content','asset','user','bug','abuse','other'])->default('other');
            $table->string('subject', 255);
            $table->text('body')->nullable();

            $table->enum('severity', ['low','medium','high','critical'])->default('low');
            $table->enum('status', ['open','triaged','in_progress','resolved','rejected','closed'])->default('open');

            // ربط اختياري بهدف البلاغ (target)
            $table->string('target_type', 50)->nullable(); // values: contents, assets, users, etc.
            $table->unsignedBigInteger('target_id')->nullable();

            // إدارة
            $table->unsignedBigInteger('assigned_admin_id')->nullable();
            $table->timestamp('closed_at')->nullable();

            // مرفق اختياري
            $table->string('attachment_path', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id','status','severity']);
            $table->index(['type','created_at']);
            $table->index(['target_type','target_id']);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('assigned_admin_id')->references('id')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
