<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_resource_youtube_meta', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('resource_id'); // type=YOUTUBE
            $t->enum('provider',['YOUTUBE'])->default('YOUTUBE');
            $t->string('channel_id',100)->nullable();
            $t->string('video_id',100)->nullable();
            $t->string('playlist_id',100)->nullable();
            $t->json('external_stats')->nullable();
            $t->timestamps();
            $t->unique('resource_id');
        });
    }
    public function down(): void { Schema::dropIfExists('med_resource_youtube_meta'); }
};