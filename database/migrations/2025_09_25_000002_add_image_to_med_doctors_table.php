<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('med_doctors', function (Blueprint $table) {
            $table->string('image', 255)->nullable()->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('med_doctors', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
