<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Support\ApiResponse;
use App\Http\Resources\Api\V1\NotificationResource;

class NotificationsController extends Controller
{
    public function index()
    {
        $u = request()->user();
        $rows = DB::table('user_notifications as un')
            ->join('notifications as n','n.id','=','un.notification_id')
            ->where('un.user_id', $u->id)
            ->select('n.id','n.title','n.body','un.status','un.read_at','n.created_at')
            ->orderBy('n.created_at','desc')
            ->limit(200)
            ->get();

        return ApiResponse::ok(NotificationResource::collection($rows));
    }

    public function markRead($id)
    {
        $u = request()->user();
        $exists = DB::table('user_notifications')->where([
            'user_id'=>$u->id,'notification_id'=>$id
        ])->exists();

        if (!$exists) {
            return ApiResponse::error('NOT_FOUND','الإشعار غير موجود.',[],404);
        }

        DB::table('user_notifications')->where([
            'user_id'=>$u->id,'notification_id'=>$id
        ])->update(['status'=>'read','read_at'=>now(),'updated_at'=>now()]);

        return ApiResponse::ok(['message'=>'تم التأشير كمقروء.']);
    }
}
