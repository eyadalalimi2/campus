<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // تأكيد عدم وجود NULL
        $nulls = DB::table('users')->whereNull('country_id')->count();
        if ($nulls > 0) {
            throw new RuntimeException("users.country_id still has {$nulls} NULL rows.");
        }
        // لا نُجري تعديلًا فعليًا؛ هذا مجرد صمام أمان يمكن حذفه لاحقًا
    }

    public function down(): void
    {
        // لا شيء
    }
};
