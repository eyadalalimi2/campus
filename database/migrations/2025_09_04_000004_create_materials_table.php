<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('scope', ['global','university'])->default('university');
            $table->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
            $table->foreignId('college_id')->nullable()->constrained('colleges')->nullOnDelete();
            $table->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete();
            $table->unsignedTinyInteger('level')->nullable();
            $table->enum('term', ['first','second','summer'])->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['scope','university_id','college_id','major_id'], 'materials_scope_university_id_college_id_major_id_index');
            $table->index(['level','term'], 'materials_level_term_index');
            $table->index('university_id', 'materials_university_id_foreign');
            $table->index('college_id', 'materials_college_id_foreign');
            $table->index('major_id', 'materials_major_id_foreign');
        });
    }

    public function down(): void {
        Schema::dropIfExists('materials');
    }
};
