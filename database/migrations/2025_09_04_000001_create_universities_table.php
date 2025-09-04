<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address', 500);
            $table->string('phone', 50)->nullable();
            $table->string('logo_path')->nullable();
            $table->string('primary_color', 20)->nullable();
            $table->string('secondary_color', 20)->nullable();
            $table->enum('theme_mode', ['auto','light','dark'])->default('auto');
            $table->boolean('is_active')->default(true);
            $table->boolean('use_default_theme')->default(false);
            $table->timestamps();

            $table->index('name', 'universities_name_idx');
        });
    }

    public function down(): void {
        Schema::dropIfExists('universities');
    }
};
