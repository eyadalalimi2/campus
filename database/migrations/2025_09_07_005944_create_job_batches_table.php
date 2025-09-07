<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('job_batches', function (Blueprint $t) {
        $t->string('id')->primary();
        $t->string('name');
        $t->integer('total_jobs');
        $t->integer('pending_jobs');
        $t->integer('failed_jobs');
        $t->longText('failed_job_ids');
        $t->mediumText('options')->nullable();
        $t->integer('cancelled_at')->nullable();
        $t->integer('created_at');
        $t->integer('finished_at')->nullable();
    });
}
public function down(): void { Schema::dropIfExists('job_batches'); }

};
