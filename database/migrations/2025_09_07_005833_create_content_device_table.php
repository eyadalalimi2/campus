<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('content_device', function (Blueprint $t) {
        $t->id();
        $t->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
        $t->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
        $t->unique(['content_id','device_id'],'content_device_content_id_device_id_unique');
    });
}
public function down(): void { Schema::dropIfExists('content_device'); }

};
