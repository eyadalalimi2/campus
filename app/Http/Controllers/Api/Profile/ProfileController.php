<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Actions\Profile\UpdateProfileInformation;
use App\Actions\Profile\UpdateUserPassword;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data'   => new UserResource($request->user()),
        ]);
    }

    public function update(UpdateProfileRequest $request, UpdateProfileInformation $action)
    {
        $user = $action->execute($request->user(), $request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'تم تحديث البيانات.',
            'data'    => new UserResource($user),
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request, UpdateUserPassword $action)
    {
        $action->execute($request->user(), $request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'تم تحديث كلمة المرور.',
        ]);
    }
}
