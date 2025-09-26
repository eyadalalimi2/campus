<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('med_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('med_subjects')->onDelete('cascade');
            $table->foreignId('topic_id')->nullable()->constrained('med_topics')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('med_resource_categories')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_url');
            $table->bigInteger('file_size_bytes')->nullable();
            $table->integer('pages_count')->nullable();
            $table->integer('order_index')->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('med_resources');
    }
};
