<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_universities', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('name',191);
            $t->string('code',50)->unique();
            $t->char('country',2);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('med_universities'); }
};