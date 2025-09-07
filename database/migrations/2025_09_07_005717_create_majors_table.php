<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('majors', function (Blueprint $t) {
            $t->id();
            $t->foreignId('college_id')->constrained('colleges')->cascadeOnDelete();
            $t->string('name');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->index('college_id');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};
