<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ad_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            // معلومات العرض
            $table->string('title', 255)->nullable();
            $table->string('image_path', 255);        // مسار الصورة (Storage)
            $table->string('target_url', 500)->nullable(); // الرابط عند الضغط
            $table->enum('placement', ['home_slider','home_top','home_bottom'])->default('home_slider');
            $table->unsignedInteger('sort_order')->default(0);

            // حالة وتوقيت
            $table->enum('status', ['draft','active','disabled','archived'])->default('draft');
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // إحصاءات بسيطة (اختيارية)
            $table->unsignedBigInteger('impressions_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);

            // علاقات إدارية
            $table->unsignedBigInteger('created_by_admin_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['placement', 'status', 'is_active']);
            $table->index(['starts_at', 'ends_at']);
            $table->foreign('created_by_admin_id')->references('id')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_banners');
    }
};
