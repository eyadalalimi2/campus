<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_resource_reference_meta', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('resource_id'); // type=REFERENCE
            $t->text('citation_text');
            $t->string('doi',100)->nullable();
            $t->string('isbn',20)->nullable();
            $t->string('pmid',50)->nullable();
            $t->string('publisher',191)->nullable();
            $t->string('edition',50)->nullable();
            $t->timestamps();
            $t->unique('resource_id');
        });
    }
    public function down(): void { Schema::dropIfExists('med_resource_reference_meta'); }
};