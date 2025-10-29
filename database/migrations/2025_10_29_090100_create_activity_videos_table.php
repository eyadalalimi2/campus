<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('activity_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_button_id')->constrained('activity_buttons')->onDelete('cascade');
            $table->string('title');
            $table->string('youtube_url');
            $table->string('cover_image')->nullable();
            $table->text('short_description')->nullable();
            $table->integer('order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_videos');
    }
};
