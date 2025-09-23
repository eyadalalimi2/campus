<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_content_flags', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('resource_id'); // med_resources.id
            $t->unsignedBigInteger('user_id')->nullable(); // مبلّغ
            $t->enum('reason',['BROKEN_LINK','RIGHTS','LOW_QUALITY','OTHER']);
            $t->enum('status',['OPEN','IN_REVIEW','CLOSED'])->default('OPEN');
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->index(['resource_id','status']);
        });
    }
    public function down(): void { Schema::dropIfExists('med_content_flags'); }
};