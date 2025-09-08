<?php

namespace App\Http\Controllers\Api\V1\Me;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Me\UpdateProfileRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\DB;

final class ProfileController extends Controller
{
    /**
     * عرض ملفي الشخصي
     */
    public function show()
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            return ApiResponse::error('UNAUTHORIZED', 'يجب تسجيل الدخول.', [], 401);
        }

        return ApiResponse::ok(new UserResource($user));
    }

    /**
     * تحديث ملفي الشخصي
     */
    public function update(UpdateProfileRequest $request)
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            return ApiResponse::error('UNAUTHORIZED', 'يجب تسجيل الدخول.', [], 401);
        }

        // القيم المُتحقَّقة
        $data = $request->validated();

        // نحدّث فقط الحقول المُرسلة (غير null)
        $updates = array_filter($data, static fn($v) => !is_null($v));

        // تحققات اتساق اختيارية قبل الحفظ: الكلية ضمن الجامعة، التخصص ضمن الكلية/الجامعة
        $universityId = $updates['university_id'] ?? null;
        $collegeId    = $updates['college_id']    ?? null;
        $majorId      = $updates['major_id']      ?? null;

        if ($collegeId && $universityId) {
            $ok = DB::table('colleges')
                ->where('id', $collegeId)
                ->where('university_id', $universityId)
                ->exists();
            if (!$ok) {
                return ApiResponse::error('INVALID_RELATION', 'الكلية لا تتبع الجامعة المحددة.', ['college_id' => ['الكلية لا تتبع الجامعة']], 422);
            }
        }

        if ($majorId) {
            $q = DB::table('majors as m')
                ->join('colleges as c', 'c.id', '=', 'm.college_id')
                ->where('m.id', $majorId);

            if ($collegeId)    { $q->where('m.college_id', $collegeId); }
            if ($universityId) { $q->where('c.university_id', $universityId); }

            if (!$q->exists()) {
                return ApiResponse::error('INVALID_RELATION', 'التخصص لا يتبع الكلية/الجامعة المحددة.', ['major_id' => ['التخصص غير متطابق']], 422);
            }
        }

        // الحفظ (Eloquent يحترم fillable في الموديل)
        if (!empty($updates)) {
            $user->fill($updates)->save();
        }

        return ApiResponse::ok(new UserResource($user->fresh()));
    }
}
