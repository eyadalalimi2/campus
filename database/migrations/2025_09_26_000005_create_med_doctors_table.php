<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('med_doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar_path')->nullable();
            $table->text('bio')->nullable();
            $table->integer('order_index')->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('med_doctors');
    }
};
