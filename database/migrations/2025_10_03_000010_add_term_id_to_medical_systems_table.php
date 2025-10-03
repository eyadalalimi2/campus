<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('MedicalSystems', function (Blueprint $table) {
            $table->unsignedBigInteger('term_id')->nullable()->after('year_id');
            $table->foreign('term_id')->references('id')->on('MedicalTerms')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('MedicalSystems', function (Blueprint $table) {
            $table->dropForeign(['term_id']);
            $table->dropColumn('term_id');
        });
    }
};