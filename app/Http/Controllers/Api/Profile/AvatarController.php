<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    /**
     * رفع/تحديث صورة البروفايل
     * يقبل الحقل: avatar أو profile_photo
     * ويخزن المسار في users.profile_photo_path
     */
    public function upsert(Request $request)
    {
        // اجعل أحد الحقلين مطلوباً
        $request->validate([
            'avatar'        => ['nullable','image','mimes:jpeg,jpg,png,webp','max:2048'],
            'profile_photo' => ['nullable','image','mimes:jpeg,jpg,png,webp','max:2048'],
        ]);

        $file = $request->file('avatar') ?? $request->file('profile_photo');
        if (! $file) {
            return response()->json([
                'message' => 'الرجاء إرفاق صورة في الحقل avatar أو profile_photo.',
            ], 422);
        }

        $user = $request->user();

        // حذف القديمة إن وُجدت
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // تخزين الجديدة على قرص public داخل profiles/
        $path = $file->store('profiles', 'public');

        // حفظ المسار في العمود الصحيح
        $user->profile_photo_path = $path;
        $user->save();

        return new UserResource($user->refresh());
    }

    /**
     * حذف صورة البروفايل وإرجاع المستخدم بدون صورة
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->profile_photo_path = null;
        $user->save();

        return response()->json([
            'message' => 'تم حذف صورة البروفايل.',
        ]);
    }
}
