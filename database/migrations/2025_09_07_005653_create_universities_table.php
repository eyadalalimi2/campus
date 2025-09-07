<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('universities', function (Blueprint $t) {
        $t->id();
        $t->string('name');
        $t->string('address',500);
        $t->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
        $t->string('phone',50)->nullable();
        $t->string('logo_path')->nullable();
        $t->string('primary_color',20)->nullable();
        $t->string('secondary_color',20)->nullable();
        $t->enum('theme_mode',['auto','light','dark'])->default('auto');
        $t->boolean('is_active')->default(true);
        $t->boolean('use_default_theme')->default(false);
        $t->timestamps();
        $t->index('name','universities_name_idx');
        $t->index('country_id','idx_univ_country');
    });
}
public function down(): void { Schema::dropIfExists('universities'); }

};
