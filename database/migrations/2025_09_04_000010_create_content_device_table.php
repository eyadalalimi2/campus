<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('content_device', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();

            $table->unique(['content_id','device_id'], 'content_device_content_id_device_id_unique');
            $table->index('device_id', 'content_device_device_id_foreign');
        });
    }

    public function down(): void {
        Schema::dropIfExists('content_device');
    }
};
