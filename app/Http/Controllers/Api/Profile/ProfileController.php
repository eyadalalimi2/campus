<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * عرض بروفايل المستخدم الحالي
     */
    public function show(Request $request)
    {
        $user = $request->user();
        return new UserResource($user);
    }

    /**
     * تحديث بيانات البروفايل الأساسية
     *
     * الحقول المسموح تحديثها: name, phone, country, university_id, college_id, major_id
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'          => ['sometimes','string','max:255'],
            'phone'         => ['sometimes','nullable','string','max:20'],
            'country'       => ['sometimes','nullable','string','max:100'],
            'university_id' => ['sometimes','nullable','integer','exists:universities,id'],
            'college_id'    => ['sometimes','nullable','integer','exists:colleges,id'],
            'major_id'      => ['sometimes','nullable','integer','exists:majors,id'],
        ]);

        // تحديث فقط الحقول المرسلة
        foreach ($validated as $key => $val) {
            $user->{$key} = $val;
        }
        $user->save();

        return new UserResource($user->refresh());
    }

    /**
     * تحديث كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password'      => ['required','string'],
            'password'              => ['required','confirmed','min:8'],
        ]);

        // التحقق من كلمة المرور الحالية
        if (! \Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'كلمة المرور الحالية غير صحيحة.',
            ], 422);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        $user->save();

        return response()->json([
            'message' => 'تم تحديث كلمة المرور بنجاح.',
        ]);
    }
}
