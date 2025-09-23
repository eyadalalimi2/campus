<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_search_terms', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('term',191);
            $t->integer('hits')->default(0);
            $t->dateTime('last_used_at')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('med_search_terms'); }
};