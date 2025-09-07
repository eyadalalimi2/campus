<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('contents', function (Blueprint $t) {
        $t->id();
        $t->string('title');
        $t->text('description')->nullable();
        $t->enum('type',['file','video','link'])->default('file');
        $t->string('source_url')->nullable();
        $t->string('file_path')->nullable();

        $t->foreignId('university_id')->constrained('universities'); // إلزامي
        $t->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete();
        $t->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();
        $t->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
        $t->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();

        $t->boolean('is_active')->default(true);
        $t->enum('status',['draft','in_review','published','archived'])->default('draft');
        $t->timestamp('published_at')->nullable();
        $t->foreignId('published_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
        $t->unsignedInteger('version')->default(1);
        $t->text('changelog')->nullable();

        $t->timestamps();
        $t->softDeletes();

        $t->index(['type','is_active'],'contents_scope_type_is_active_index');
        $t->index(['status','is_active','published_at'],'idx_contents_pub');
        $t->index(['university_id','college_id','major_id','status','is_active'],'idx_contents_scope_keys');
        $t->index(['status','is_active','university_id','college_id','major_id','created_at'],'idx_contents_feed');
    });
}
public function down(): void { Schema::dropIfExists('contents'); }

};
