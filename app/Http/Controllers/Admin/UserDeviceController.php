<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserDevice;
use Illuminate\Http\Request;

class UserDeviceController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $devices = UserDevice::query()
            ->with('user:id,name,email')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->whereHas('user', function ($uq) use ($q) {
                    $uq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%");
                })->orWhere('device_name', 'like', "%{$q}%")
                  ->orWhere('device_model', 'like', "%{$q}%")
                  ->orWhere('ip_address', 'like', "%{$q}%")
                  ->orWhere('device_identifier', 'like', "%{$q}%");
            })
            ->orderByDesc('last_login_at')
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.user_devices.index', compact('devices', 'q'));
    }

    public function destroy(UserDevice $user_device)
    {
        $user_device->delete();
        return back()->with('success', 'تم حذف الجهاز من الحساب. يمكن للمستخدم تسجيل الدخول من جهاز آخر الآن.');
    }
}
