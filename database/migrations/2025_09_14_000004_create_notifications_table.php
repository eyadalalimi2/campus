<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id'); // صاحب الإشعار
            $table->string('title', 255);
            $table->text('body')->nullable();

            $table->enum('type', [
                'content_created','content_updated','content_deleted',
                'asset_created','asset_updated','asset_deleted',
                'system','other'
            ])->default('system');

            $table->json('data')->nullable();      // معلومات إضافية (IDs, links, ...)
            $table->timestamp('read_at')->nullable();

            // ربط اختياري مباشر
            $table->unsignedBigInteger('content_id')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();

            $table->timestamps();

            $table->index(['user_id','type','created_at']);
            $table->index(['read_at']);

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('content_id')->references('id')->on('contents')->nullOnDelete();
            $table->foreign('asset_id')->references('id')->on('assets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
