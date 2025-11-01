<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1..5
            $table->text('comment')->nullable();

            // حالة الظهور/المراجعة: pending/approved/rejected
            $table->string('status', 20)->default('pending');
            $table->index('status');

            // Admin reply (single official reply)
            $table->text('reply_text')->nullable();
            $table->foreignId('reply_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
