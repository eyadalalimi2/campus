<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_resource_files', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('resource_id'); // med_resources.id
            $t->string('storage_path',255);        // s3 path
            $t->string('cdn_url',255)->nullable();
            $t->unsignedBigInteger('bytes')->nullable();
            $t->char('hash_sha256',64)->nullable();
            $t->boolean('download_allowed')->default(false);
            $t->timestamps();
            $t->index('resource_id');
        });
    }
    public function down(): void { Schema::dropIfExists('med_resource_files'); }
};