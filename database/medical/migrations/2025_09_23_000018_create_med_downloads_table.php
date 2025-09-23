<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_downloads', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('user_id');          // لاحقًا med_users
            $t->unsignedBigInteger('resource_file_id'); // med_resource_files.id
            $t->enum('status',['QUEUED','DOWNLOADING','READY','FAILED'])->default('QUEUED');
            $t->dateTime('last_sync_at')->nullable();
            $t->timestamps();
            $t->index(['user_id','resource_file_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('med_downloads'); }
};