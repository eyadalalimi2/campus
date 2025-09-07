<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('academic_terms', function (Blueprint $t) {
        $t->id();
        $t->foreignId('calendar_id')->constrained('academic_calendars')->cascadeOnDelete();
        $t->enum('name',['first','second','summer']);
        $t->date('starts_on');
        $t->date('ends_on');
        $t->boolean('is_active')->default(true);
        $t->timestamps();
        $t->index(['calendar_id','is_active','starts_on']);
    });
}
public function down(): void { Schema::dropIfExists('academic_terms'); }

};
