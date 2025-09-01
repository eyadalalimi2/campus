<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('contents', 'material_id')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->foreignId('material_id')->nullable()->after('major_id')
                      ->constrained()->nullOnDelete();
            });
        }

        if (!Schema::hasTable('content_device')) {
            Schema::create('content_device', function (Blueprint $table) {
                $table->id();
                $table->foreignId('content_id')->constrained()->cascadeOnDelete();
                $table->foreignId('device_id')->constrained()->cascadeOnDelete();
                $table->unique(['content_id','device_id']);
            });
        }

        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('material_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('content_task')) {
            Schema::create('content_task', function (Blueprint $table) {
                $table->id();
                $table->foreignId('content_id')->constrained()->cascadeOnDelete();
                $table->foreignId('task_id')->constrained()->cascadeOnDelete();
                $table->unique(['content_id','task_id']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('content_task')) Schema::drop('content_task');
        if (Schema::hasTable('tasks')) Schema::drop('tasks');
        if (Schema::hasTable('content_device')) Schema::drop('content_device');

        if (Schema::hasColumn('contents', 'material_id')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropConstrainedForeignId('material_id');
            });
        }
    }
};
