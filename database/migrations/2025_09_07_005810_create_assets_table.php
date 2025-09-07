<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('assets', function (Blueprint $t) {
        $t->id();
        $t->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
        $t->foreignId('device_id')->nullable()->constrained('devices')->nullOnDelete();
        $t->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
        $t->foreignId('discipline_id')->nullable()->constrained('disciplines')->nullOnDelete();
        $t->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();

        $t->enum('category',['youtube','file','reference','question_bank','curriculum','book']);
        $t->string('title');
        $t->text('description')->nullable();

        $t->enum('status',['draft','in_review','published','archived'])->default('draft');
        $t->timestamp('published_at')->nullable();
        $t->foreignId('published_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();

        $t->string('video_url')->nullable();
        $t->string('file_path')->nullable();
        $t->string('external_url')->nullable();
        $t->boolean('is_active')->default(true);
        $t->timestamps();
        $t->softDeletes();

        $t->index(['category','material_id','device_id','doctor_id','is_active'],'assets_category_material_id_device_id_doctor_id_is_active_index');
        $t->index(['status','is_active','published_at'],'idx_assets_pub');
        $t->index(['discipline_id','program_id'],'idx_assets_disc_prog');
        $t->index(['status','is_active','discipline_id','program_id','created_at'],'idx_assets_feed');
    });
}
public function down(): void { Schema::dropIfExists('assets'); }

};
