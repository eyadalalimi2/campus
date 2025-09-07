<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void {
    Schema::create('failed_jobs', function (Blueprint $t) {
        $t->id();
        $t->char('uuid',36)->unique();
        $t->text('connection');
        $t->text('queue');
        $t->longText('payload');
        $t->longText('exception');
        $t->timestamp('failed_at')->useCurrent();
    });
}
public function down(): void { Schema::dropIfExists('failed_jobs'); }

};
