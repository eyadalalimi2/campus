<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('med_systems', function (Blueprint $table) {
            $table->dropColumn('code');
        });
        Schema::table('med_subjects', function (Blueprint $table) {
            $table->dropColumn('code');
        });
        Schema::table('med_universities', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
    public function down()
    {
        Schema::table('med_systems', function (Blueprint $table) {
            $table->string('code', 50)->unique()->nullable();
        });
        Schema::table('med_subjects', function (Blueprint $table) {
            $table->string('code', 50)->unique()->nullable();
        });
        Schema::table('med_universities', function (Blueprint $table) {
            $table->string('code', 50)->unique()->nullable();
        });
    }
};
