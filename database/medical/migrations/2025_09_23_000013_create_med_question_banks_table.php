<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_question_banks', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('resource_id'); // med_resources.id (type=QUESTION_BANK)
            $t->integer('total_questions')->default(0);
            $t->json('coverage')->nullable(); // LOs/Systems
            $t->timestamps();
            $t->unique('resource_id');
        });
    }
    public function down(): void { Schema::dropIfExists('med_question_banks'); }
};