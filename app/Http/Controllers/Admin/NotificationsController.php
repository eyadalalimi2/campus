<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = DB::table('notifications')
            ->orderBy('created_at','desc')->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        // فورم لإرسال إشعار عام (broadcast) أو لفئة محددة (university/college/major) أو لمستخدم محدد
        return view('admin.notifications.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'        => ['required','string','max:150'],
            'body'         => ['required','string','max:1000'],
            'target_type'  => ['required','in:all,university,major,user'],
            'target_id'    => ['nullable','integer'],
            'send_email'   => ['nullable','boolean'],
        ]);

        $data['created_at'] = now(); $data['updated_at'] = now();
        $notifId = DB::table('notifications')->insertGetId([
            'title'       => $data['title'],
            'body'        => $data['body'],
            'target_type' => $data['target_type'],
            'target_id'   => $data['target_id'] ?? null,
            'created_at'  => $data['created_at'],
            'updated_at'  => $data['updated_at'],
        ]);

        // بناء قائمة المستلمين حسب الهدف، وإدراجها في user_notifications (حالة unread)
        $usersQ = DB::table('users');
        if ($data['target_type'] === 'university') {
            $usersQ->where('university_id', $data['target_id']);
        } elseif ($data['target_type'] === 'major') {
            $usersQ->where('major_id', $data['target_id']);
        } elseif ($data['target_type'] === 'user') {
            $usersQ->where('id', $data['target_id']);
        } // else all

        $users = $usersQ->select('id')->limit(100000)->get();
        $bulk = [];
        $now = now();
        foreach ($users as $u) {
            $bulk[] = [
                'notification_id' => $notifId,
                'user_id'         => $u->id,
                'status'          => 'unread',
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }
        if ($bulk) DB::table('user_notifications')->insert($bulk);

        // خيار إرسال بريد (اختياري) — فعّل Mailer عند الحاجة
        // if (!empty($data['send_email'])) { ... }

        return redirect()->route('admin.notifications.index')->with('ok','تم إنشاء الإشعار وإرساله.');
    }
}
