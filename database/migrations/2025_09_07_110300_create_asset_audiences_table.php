<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_audiences', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('major_id');

            // منع التكرار لنفس الأصل/التخصص
            $table->unique(['asset_id', 'major_id'], 'uq_asset_audience');

            // فهارس لتسريع الاستعلامات
            $table->index('asset_id', 'idx_aa_asset');
            $table->index('major_id', 'idx_aa_major');

            // علاقات بمسح متسلسل
            $table->foreign('asset_id')
                ->references('id')->on('assets')
                ->onDelete('cascade');

            $table->foreign('major_id')
                ->references('id')->on('majors')
                ->onDelete('cascade');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('asset_audiences', function (Blueprint $table) {
            $table->dropForeign(['asset_id']);
            $table->dropForeign(['major_id']);
        });

        Schema::dropIfExists('asset_audiences');
    }
};
