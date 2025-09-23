<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_audit_logs', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->string('auditable_type',100);
            $t->unsignedBigInteger('auditable_id');
            $t->enum('action',['CREATE','UPDATE','DELETE','PUBLISH','ARCHIVE']);
            $t->unsignedBigInteger('actor_admin_id')->nullable(); // admins.id من نظامك
            $t->json('data_before')->nullable();
            $t->json('data_after')->nullable();
            $t->string('ip',45)->nullable();
            $t->timestamp('created_at')->useCurrent();
        });
    }
    public function down(): void { Schema::dropIfExists('med_audit_logs'); }
};