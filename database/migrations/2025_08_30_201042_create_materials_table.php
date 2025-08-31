<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('materials', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('code')->nullable();

            // نطاق المادة: عام/خاص
            $t->enum('scope', ['global','university'])->default('university');

            // ربط اختياري عند الخاص
            $t->foreignId('university_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('college_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('major_id')->nullable()->constrained()->nullOnDelete();

            // المستوى والفترة
            $t->unsignedTinyInteger('level')->nullable(); // 1..N
            $t->enum('term', ['first','second','summer'])->nullable();

            $t->boolean('is_active')->default(true);
            $t->timestamps();

            $t->index(['scope','university_id','college_id','major_id']);
            $t->index(['level','term']);
        });
    }
    public function down(): void { Schema::dropIfExists('materials'); }
};
