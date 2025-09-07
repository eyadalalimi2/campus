<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // إنشاء خطة "standard" إن لم تكن موجودة
        $exists = DB::table('plans')->where('code', 'standard')->exists();
        if (!$exists) {
            DB::table('plans')->insert([
                'code'         => 'standard',
                'name'         => 'Standard',
                'price_cents'  => null,  // اتركها null إن كان السعر يُدار من مكان آخر
                'currency'     => 'YER',
                'billing_cycle'=> 'monthly',
                'is_active'    => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // أمثلة مزايا (اختياري)
        $planId = DB::table('plans')->where('code', 'standard')->value('id');
        if ($planId) {
            // لا نكرر المفاتيح
            $kv = [
                'max_devices'   => '1',
                'offline_access'=> '0',
            ];
            foreach ($kv as $k => $v) {
                $exists = DB::table('plan_features')
                    ->where('plan_id', $planId)
                    ->where('feature_key', $k)
                    ->exists();
                if (!$exists) {
                    DB::table('plan_features')->insert([
                        'plan_id'      => $planId,
                        'feature_key'  => $k,
                        'feature_value'=> $v,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // لا نحذف الخطة افتراضياً لتجنّب كسر FK لاحقاً
    }
};
