<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('med_resource_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');   // مثل: ملفات، تفريغات، أسئلة، مراجع
            $table->string('code')->unique(); // files, notes, questions, references
            $table->integer('order_index')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('med_resource_categories');
    }
};
