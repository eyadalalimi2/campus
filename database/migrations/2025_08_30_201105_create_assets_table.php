<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('material_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('device_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();

            $t->enum('category', ['youtube','file','reference','question_bank','curriculum','book']);

            $t->string('title');
            $t->text('description')->nullable();

            // مصادر حسب النوع
            $t->string('video_url')->nullable();     // لليوتيوب
            $t->string('file_path')->nullable();     // لملفات مرفوعة
            $t->string('external_url')->nullable();  // لمراجع/روابط كتب

            // تفعيل
            $t->boolean('is_active')->default(true);
            $t->timestamps();

            $t->index(['category','material_id','device_id','doctor_id','is_active']);
        });
    }
    public function down(): void { Schema::dropIfExists('assets'); }
};
