<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_questions', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('resource_id'); // بنك
            $t->unsignedBigInteger('subject_id');  // med_subjects.id
            $t->unsignedBigInteger('system_id')->nullable(); // med_systems.id
            $t->enum('type',['MCQ','OSCE','SAQ'])->default('MCQ');
            $t->text('stem'); // نص السؤال
            $t->enum('difficulty',['EASY','MEDIUM','HARD']);
            $t->enum('bloom',['REMEMBER','UNDERSTAND','APPLY','ANALYZE','EVALUATE','CREATE'])->nullable();
            $t->json('tags')->nullable();
            $t->text('explanation')->nullable();
            $t->string('source_ref',255)->nullable();
            $t->timestamps();
            $t->index(['subject_id','system_id','type','difficulty'],'med_q_idx');
        });
    }
    public function down(): void { Schema::dropIfExists('med_questions'); }
};