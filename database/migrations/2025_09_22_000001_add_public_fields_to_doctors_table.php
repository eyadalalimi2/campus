<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('doctors', function (Blueprint $table) {
            $table->unsignedBigInteger('public_college_id')->nullable()->after('major_id');
            $table->unsignedBigInteger('public_major_id')->nullable()->after('public_college_id');
        });
    }

    public function down(): void {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['public_college_id', 'public_major_id']);
        });
    }
};
