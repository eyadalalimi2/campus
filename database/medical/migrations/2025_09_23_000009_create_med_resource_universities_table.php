<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_resource_universities', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('resource_id');   // med_resources.id
            $t->unsignedBigInteger('university_id'); // med_universities.id
            $t->timestamps();
            $t->unique(['resource_id','university_id'],'med_ru_unique');
        });
    }
    public function down(): void { Schema::dropIfExists('med_resource_universities'); }
};