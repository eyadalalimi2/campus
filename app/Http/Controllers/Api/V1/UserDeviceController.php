<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserDeviceResource;
use Illuminate\Http\Request;

class UserDeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = $request->user()->devices()->orderByDesc('last_login_at')->get();
        return UserDeviceResource::collection($devices);
    }
}
