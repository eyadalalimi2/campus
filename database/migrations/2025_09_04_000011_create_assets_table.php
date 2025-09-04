<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
            $table->foreignId('device_id')->nullable()->constrained('devices')->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->enum('category', ['youtube','file','reference','question_bank','curriculum','book']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('material_id', 'assets_material_id_foreign');
            $table->index('device_id', 'assets_device_id_foreign');
            $table->index('doctor_id', 'assets_doctor_id_foreign');
            $table->index(['category','material_id','device_id','doctor_id','is_active'], 'assets_category_material_id_device_id_doctor_id_is_active_index');
        });
    }

    public function down(): void {
        Schema::dropIfExists('assets');
    }
};

