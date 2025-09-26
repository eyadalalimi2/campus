<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('med_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('med_doctors')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('med_subjects')->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained('med_topics')->onDelete('cascade');
            $table->string('title');
            $table->string('thumbnail_url')->nullable();
            $table->string('youtube_url');
            $table->integer('order_index')->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['youtube_url', 'subject_id', 'topic_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('med_videos');
    }
};
