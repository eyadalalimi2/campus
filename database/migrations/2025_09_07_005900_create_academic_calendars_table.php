<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('academic_calendars', function (Blueprint $t) {
        $t->id();
        $t->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
        $t->string('year_label',20);
        $t->date('starts_on');
        $t->date('ends_on');
        $t->boolean('is_active')->default(true);
        $t->timestamps();
        $t->index(['university_id','is_active','starts_on']);
    });
}
public function down(): void { Schema::dropIfExists('academic_calendars'); }

};
