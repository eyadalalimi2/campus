<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('colleges', function (Blueprint $t) {
            $t->id();
            $t->foreignId('university_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('code', 20)->nullable(); // ENG, MED ...
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('colleges'); }
};
