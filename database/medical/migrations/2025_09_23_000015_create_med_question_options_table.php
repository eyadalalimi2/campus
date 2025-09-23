<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_question_options', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('question_id'); // med_questions.id
            $t->text('option_text');
            $t->boolean('is_correct')->default(false);
            $t->timestamps();
            $t->index('question_id');
        });
    }
    public function down(): void { Schema::dropIfExists('med_question_options'); }
};