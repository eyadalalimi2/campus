<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('med_favorites', function (Blueprint $t) {
            $t->bigIncrements('id');
            $t->unsignedBigInteger('user_id');     // مستخدم تطبيق الطب (إن قررت لاحقًا med_users)
            $t->unsignedBigInteger('resource_id'); // med_resources.id
            $t->timestamps();
            $t->unique(['user_id','resource_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('med_favorites'); }
};