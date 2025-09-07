<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('major_program', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('major_id');
            $table->unsignedBigInteger('program_id');

            // فريد لمنع التكرار
            $table->unique(['major_id', 'program_id'], 'uq_major_program');

            // فهارس لكل مفتاح
            $table->index('major_id', 'idx_mp_major');
            $table->index('program_id', 'idx_mp_program');

            // علاقات بمسح متسلسل لضمان نظافة البيانات
            $table->foreign('major_id')
                ->references('id')->on('majors')
                ->onDelete('cascade');

            $table->foreign('program_id')
                ->references('id')->on('programs')
                ->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('major_program', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->dropForeign(['program_id']);
        });

        Schema::dropIfExists('major_program');
    }
};
