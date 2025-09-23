<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_doctors', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('name',191);
            $t->string('channel_url',255);
            $t->char('country',2)->nullable();
            $t->boolean('verified')->default(false);
            $t->decimal('score',4,2)->default(0);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('med_doctors'); }
};