<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateAvatarRequest;
use App\Actions\Profile\UpdateUserAvatar;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function upsert(UpdateAvatarRequest $request, UpdateUserAvatar $action)
    {
        $user = $action->execute($request->user(), $request->file('avatar'));

        return response()->json([
            'status'  => 'success',
            'message' => 'تم تحديث الصورة الشخصية.',
            'data'    => ['avatar_url' => $user->avatar_url],
        ]);
    }

    public function destroy(Request $request, UpdateUserAvatar $action)
    {
        $user = $action->delete($request->user());

        return response()->json([
            'status'  => 'success',
            'message' => 'تم حذف الصورة الشخصية.',
            'data'    => ['avatar_url' => $user->avatar_url],
        ]);
    }
}
