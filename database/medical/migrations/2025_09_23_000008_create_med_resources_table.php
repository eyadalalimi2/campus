<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('med_resources', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->enum('type',['YOUTUBE','BOOK','SUMMARY','REFERENCE','QUESTION_BANK']);
            $t->enum('track',['BASIC','CLINICAL']);
            $t->unsignedBigInteger('subject_id');    // med_subjects.id (إلزامي)
            $t->unsignedBigInteger('system_id')->nullable(); // med_systems.id (Basic إلزامي، Clinical اختياري)
            $t->unsignedBigInteger('doctor_id')->nullable(); // med_doctors.id
            $t->string('title',255);
            $t->string('title_en',255)->nullable();
            $t->text('description')->nullable();
            $t->char('language',2)->default('ar');
            $t->char('country',2)->nullable();
            $t->smallInteger('year')->nullable();
            $t->json('authors')->nullable();
            $t->enum('level',['basic','advanced'])->default('basic');
            $t->decimal('rating',3,2)->default(0);
            $t->integer('popularity')->default(0);
            $t->integer('duration_min')->nullable();
            $t->decimal('size_mb',8,2)->nullable();
            $t->string('cover_url',255)->nullable();
            $t->string('source_url',255)->nullable();
            $t->enum('license',['OPEN','LINK_ONLY','RESTRICTED'])->default('LINK_ONLY');
            $t->enum('visibility',['PUBLIC','RESTRICTED'])->default('PUBLIC');
            $t->enum('status',['DRAFT','PUBLISHED','ARCHIVED'])->default('PUBLISHED');
            $t->unsignedBigInteger('created_by')->nullable(); // admins.id في نظامك الحالي
            $t->timestamps();

            $t->index(['subject_id','system_id','type','track','visibility','status'],'med_resources_idx');
        });
    }
    public function down(): void { Schema::dropIfExists('med_resources'); }
};