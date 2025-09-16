<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');

            // موقع البانر (بما أن المطلوب home فقط)
            $table->enum('placement', ['home'])->default('home');

            // بيانات العرض
            $table->string('title', 150)->nullable();
            $table->string('image_path', 255);              // مسار الصورة داخل التخزين
            $table->string('image_alt', 150)->nullable();

            // الرابط عند الضغط
            $table->string('target_url', 500)->nullable();
            $table->boolean('open_external')->default(true);

            // حالة وتوقيت العرض
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('ends_at')->nullable()->index();

            // ترتيب العرض
            $table->integer('sort_order')->default(0)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
