<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('med_systems', function (Blueprint $t) {
            $t->bigIncrements('id');

            $t->string('name_ar', 191);
            $t->string('name_en', 191)->nullable();
            $t->string('icon_url', 255)->nullable();
            $t->integer('display_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('med_systems'); }
};