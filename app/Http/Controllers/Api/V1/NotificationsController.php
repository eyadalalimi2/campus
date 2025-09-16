<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    // GET /api/v1/me/notifications?type=&unread=1&q=
    public function index(Request $request)
    {
        $user = $request->user();

        $q = Notification::query()
            ->where('user_id', $user->id)
            ->when($request->filled('type'), fn($w) => $w->where('type', $request->type))
            ->when($request->filled('unread'), function ($w) use ($request) {
                return $request->unread ? $w->whereNull('read_at') : $w;
            })
            ->when($request->filled('q'), function ($w) use ($request) {
                $term = $request->q;
                return $w->where(fn($x) => $x->where('title','like',"%{$term}%")
                                              ->orWhere('body','like',"%{$term}%"));
            })
            ->latest('created_at');

        $page = $q->paginate(20)->withQueryString();

        return NotificationResource::collection($page)->additional(['status' => 'ok']);
    }

    // GET /api/v1/me/notifications/{id}  (لا يغيّر حالة القراءة)
    public function show(Request $request, $id)
    {
        $n = Notification::where('user_id', $request->user()->id)->findOrFail($id);
        return (new NotificationResource($n))->additional(['status' => 'ok']);
    }

    // PATCH /api/v1/me/notifications/{id}/read
    public function markRead(Request $request, $id)
    {
        $n = Notification::where('user_id', $request->user()->id)->findOrFail($id);
        if (!$n->read_at) {
            $n->read_at = now();
            $n->save();
        }
        return response()->json(['status' => 'ok', 'read_at' => optional($n->read_at)->toISOString()]);
    }

    // PATCH /api/v1/me/notifications/read-all
    public function markAllRead(Request $request)
    {
        $userId = $request->user()->id;
        Notification::where('user_id',$userId)->whereNull('read_at')
            ->update(['read_at' => now(), 'updated_at' => now()]);
        return response()->json(['status' => 'ok']);
    }

    // DELETE /api/v1/me/notifications/{id}
    public function destroy(Request $request, $id)
    {
        $n = Notification::where('user_id', $request->user()->id)->findOrFail($id);
        $n->delete();
        return response()->json(['status' => 'deleted']);
    }
}
