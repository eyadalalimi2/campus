<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_question_stats', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('question_id')->unique(); // med_questions.id
            $t->integer('attempts')->default(0);
            $t->integer('correct')->default(0);
            $t->decimal('correct_rate',5,2)->default(0);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('med_question_stats'); }
};