<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activation_code_batches', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('university_id')->nullable();
            $table->unsignedBigInteger('college_id')->nullable();
            $table->unsignedBigInteger('major_id')->nullable();

            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('duration_days')->default(365);

            $table->enum('start_policy', ['on_redeem','fixed_start'])->default('on_redeem');
            $table->date('starts_on')->nullable();

            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();

            $table->string('code_prefix', 24)->nullable();
            $table->unsignedTinyInteger('code_length')->default(14);

            $table->unsignedBigInteger('created_by_admin_id')->nullable();

            $table->timestamps();

            $table->index(['plan_id', 'university_id']);
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('restrict');
            $table->foreign('university_id')->references('id')->on('universities')->onDelete('set null');
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('set null');
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('set null');
            $table->foreign('created_by_admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activation_code_batches');
    }
};
