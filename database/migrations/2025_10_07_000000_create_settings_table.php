<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('dashboard_logo')->nullable();
            $table->string('dashboard_favicon')->nullable();
            $table->string('admin_login_logo')->nullable();
            $table->string('site_title')->nullable();
            $table->text('site_short_description')->nullable();
            $table->longText('site_long_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
