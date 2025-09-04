<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['file','video','link'])->default('file');
            $table->string('source_url')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('scope', ['global','university'])->default('university');
            $table->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
            $table->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete();
            $table->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['scope','type','is_active'], 'contents_scope_type_is_active_index');
            $table->index(['university_id','college_id','major_id'], 'contents_university_id_college_id_major_id_index');
            $table->index('doctor_id', 'contents_doctor_id_index');
            $table->index('college_id', 'contents_college_id_foreign');
            $table->index('major_id', 'contents_major_id_foreign');
            $table->index('material_id', 'contents_material_id_foreign');
        });
    }

    public function down(): void {
        Schema::dropIfExists('contents');
    }
};
