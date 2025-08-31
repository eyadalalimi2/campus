<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('universities', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();      // مثال: sanaa, aden
            $t->string('code', 20)->unique();  // اختياري: SAN, ADN
            $t->string('logo_path')->nullable();
            $t->string('favicon_path')->nullable();
            $t->string('primary_color', 20)->default('#0d6efd');
            $t->string('secondary_color', 20)->default('#6c757d');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('universities'); }
};
