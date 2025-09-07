<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('countries', function (Blueprint $t) {
        $t->id();
        $t->string('name_ar',150);
        $t->char('iso2',2)->nullable()->unique();
        $t->string('phone_code',10)->nullable();
        $t->char('currency_code',3)->nullable();
        $t->boolean('is_active')->default(true);
        $t->timestamps();
    });
}
public function down(): void { Schema::dropIfExists('countries'); }

};
