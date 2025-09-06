<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name'            => $data['name'],
            'email'           => $data['email'],
            'phone'           => $data['phone'] ?? null,
            'student_number'  => $data['student_number'] ?? null,
            'country'         => $data['country'] ?? null,
            'password'        => Hash::make($data['password']),
            'university_id'   => $data['university_id'] ?? null,
            'college_id'      => $data['college_id'] ?? null,
            'major_id'        => $data['major_id'] ?? null,
        ]);

        event(new Registered($user)); // يرسل رسالة تفعيل البريد

        return response()->json([
            'status'  => 'success',
            'message' => 'تم إنشاء الحساب. تحقق من بريدك الإلكتروني لتفعيل الحساب.',
            'data'    => new UserResource($user),
        ], 201);
    }
}
