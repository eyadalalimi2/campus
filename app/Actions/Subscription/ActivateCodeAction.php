<?php

namespace App\Actions\Subscription;

use Illuminate\Support\Facades\DB;
use App\Exceptions\Api\ApiException;
use Illuminate\Database\QueryException;

final class ActivateCodeAction
{
    /**
     * تفعيل كود اشتراك لمستخدم.
     *
     * @param  int    $userId
     * @param  string $code    قيمة الكود كما أدخلها المستخدم
     * @return array           صف الاشتراك المُنشأ
     *
     * @throws ApiException
     */
    public function handle(int $userId, string $code): array
    {
        $code = trim($code);

        // جلب الكود + بيانات الخطة (للاستخدام في العملة والسعر)
        $ac = DB::table('activation_codes as ac')
            ->leftJoin('plans as p', 'p.id', '=', 'ac.plan_id')
            ->select(
                'ac.id as ac_id', 'ac.plan_id',
                'p.currency as plan_currency', 'p.price_cents as plan_price_cents'
            )
            ->where('ac.code', $code)
            ->first();

        if (!$ac) {
            throw new ApiException('ACTIVATION_CODE_NOT_FOUND', 'لم يتم العثور على كود التفعيل.', 404);
        }

        // قيم افتراضية للعملة/السعر إن لم تتوفر بالخطة (سلامة فقط)
        $currency    = $ac->plan_currency ?: 'YER';
        $priceCents  = $ac->plan_price_cents; // يمكن أن يكون NULL حسب نموذج التسعير

        try {
            return DB::transaction(function () use ($userId, $ac, $currency, $priceCents) {

                // الإدخال — تريغرز MariaDB على جدول subscriptions ستتولّى:
                // - التحقق من حالة الكود وصلاحيته ونطاقه للمستخدم
                // - فرض plan_id و started_at و ends_at و status
                // - تحديث سجلات activation_codes (عداد الاستخدام..إلخ) بعد الإدخال
                $subId = DB::table('subscriptions')->insertGetId([
                    'user_id'            => $userId,
                    'activation_code_id' => $ac->ac_id,
                    'plan_id'            => $ac->plan_id,     // سيُفرض أيضًا داخل التريغر
                    'status'             => 'active',          // التريغر قد يعدّله لو لزم
                    'started_at'         => null,              // يُحسب بالتريغر حسب سياسة البدء
                    'ends_at'            => null,              // يُحسب بالتريغر حسب المدة
                    'auto_renew'         => 0,
                    'price_cents'        => $priceCents,       // خيار: سعر الخطة وقت التفعيل
                    'currency'           => $currency,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);

                $sub = DB::table('subscriptions')->where('id', $subId)->first();
                return (array) $sub;
            });
        } catch (QueryException $qe) {
            // رسائل SIGNAL من التريغرز كما عرّفتها في SQL:
            $msg = $qe->getMessage();
            $mapped = $this->mapTriggerError($msg);
            if ($mapped) {
                throw new ApiException($mapped['code'], 'تعذّر تفعيل الكود: '.$mapped['message'], 409);
            }
            // غير مُعرّف صراحة
            throw new ApiException('ACTIVATION_FAILED', 'فشل تفعيل الكود.', 409);
        } catch (\Throwable $e) {
            // أي خطأ عام
            throw new ApiException('ACTIVATION_FAILED', 'فشل تفعيل الكود.', 409);
        }
    }

    /**
     * مطابقة رسالة خطأ التريغر إلى كود/رسالة عربية واضحة.
     *
     * @param  string $dbMessage
     * @return array{code:string,message:string}|null
     */
    private function mapTriggerError(string $dbMessage): ?array
    {
        // عبارات كما في التريغرز:
        // 'Activation code not found'
        // 'Activation code is not active'
        // 'Activation code not yet valid'
        // 'Activation code expired'
        // 'Activation code already redeemed'
        // 'Activation code restricted to another university'
        // 'Activation code restricted to another college'
        // 'Activation code restricted to another major'

        $map = [
            'Activation code is not active'                     => ['ACTIVATION_CODE_NOT_ACTIVE',        'الكود غير مُفعّل.'],
            'Activation code not yet valid'                     => ['ACTIVATION_CODE_NOT_YET_VALID',     'الكود غير صالح بعد.'],
            'Activation code expired'                           => ['ACTIVATION_CODE_EXPIRED',           'انتهت صلاحية الكود.'],
            'Activation code already redeemed'                  => ['ACTIVATION_CODE_ALREADY_REDEEMED',  'الكود مستخدم مسبقًا.'],
            'Activation code restricted to another university'  => ['RESTRICTED_UNIVERSITY',             'الكود مقيّد بجامعة أخرى.'],
            'Activation code restricted to another college'     => ['RESTRICTED_COLLEGE',                'الكود مقيّد بكلية أخرى.'],
            'Activation code restricted to another major'       => ['RESTRICTED_MAJOR',                  'الكود مقيّد بتخصص آخر.'],
            // في حال وصلتنا رسالة "Activation code not found" من التريغر (حالة نادرة)
            'Activation code not found'                         => ['ACTIVATION_CODE_NOT_FOUND',         'لم يتم العثور على كود التفعيل.'],
        ];

        foreach ($map as $needle => [$code, $ar]) {
            if (stripos($dbMessage, $needle) !== false) {
                return ['code' => $code, 'message' => $ar];
            }
        }
        return null;
    }
}
