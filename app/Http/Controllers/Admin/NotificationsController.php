<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $q = Notification::query()->with('user')
            ->when($request->filled('user_id'), fn($w) => $w->where('user_id', $request->user_id))
            ->when($request->filled('type'), fn($w) => $w->where('type', $request->type))
            ->when($request->filled('read'), function ($w) use ($request) {
                return $request->read === '1' ? $w->whereNotNull('read_at') : $w->whereNull('read_at');
            })
            ->when($request->filled('q'), function ($w) use ($request) {
                $t = $request->q;
                return $w->where(fn($x) => $x->where('title', 'like', "%{$t}%")->orWhere('body', 'like', "%{$t}%"));
            })
            ->latest('created_at');

        $notifications = $q->paginate(20)->withQueryString();

        $types = [
            'content_created',
            'content_updated',
            'content_deleted',
            'asset_created',
            'asset_updated',
            'asset_deleted',
            'review_reply',
            'system',
            'other'
        ];

        return view('admin.notifications.index', compact('notifications', 'types'));
    }

    public function create()
    {
        $types = [
            'content_created',
            'content_updated',
            'content_deleted',
            'asset_created',
            'asset_updated',
            'asset_deleted',
            'review_reply',
            'system',
            'other'
        ];
        return view('admin.notifications.create', compact('types'));
    }

    public function store(Request $request)
    {
        // target_type: all | users | university | college | major
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string|max:5000',
            'action_url'   => 'nullable|url|max:1000',
            'image_url'    => 'nullable|url|max:1000',
            'type'         => 'nullable|in:content_created,content_updated,content_deleted,asset_created,asset_updated,asset_deleted,review_reply,system,other',

            'target_type'  => 'required|in:all,users,university,college,major',

            // users
            'user_ids'     => 'nullable|array',
            'user_ids.*'   => 'integer|exists:users,id',

            // cascade
            'university_id' => 'nullable|integer|exists:universities,id',
            'college_id'   => 'nullable|integer|exists:colleges,id',
            'major_id'     => 'nullable|integer|exists:majors,id',

            'dispatch_now' => 'nullable|in:0,1',
        ]);

        $type = $data['type'] ?? 'system';
        $now  = now();

        // JSON داخل data
        $dataJson = array_filter([
            'action_url' => $data['action_url'] ?? null,
            'image_url'  => $data['image_url']  ?? null,
        ], fn($v) => !is_null($v) && $v !== '');

        // تحقق هرمي صريح
        if ($data['target_type'] === 'university') {
            if (empty($data['university_id'])) {
                return back()->withInput()->withErrors(['university_id' => 'يجب اختيار جامعة.']);
            }
        }

        if ($data['target_type'] === 'college') {
            if (empty($data['university_id']) || empty($data['college_id'])) {
                return back()->withInput()->withErrors(['college_id' => 'اختر الجامعة ثم الكلية.']);
            }
            // تأكد أن الكلية ضمن الجامعة
            $ok = \App\Models\College::where('id', $data['college_id'])
                ->where('university_id', $data['university_id'])->exists();
            if (!$ok) {
                return back()->withInput()->withErrors(['college_id' => 'هذه الكلية لا تنتمي للجامعة المحددة.']);
            }
        }

        if ($data['target_type'] === 'major') {
            if (empty($data['university_id']) || empty($data['college_id']) || empty($data['major_id'])) {
                return back()->withInput()->withErrors(['major_id' => 'اختر الجامعة ثم الكلية ثم التخصص.']);
            }
            // تأكد أن الكلية ضمن الجامعة
            $okCol = \App\Models\College::where('id', $data['college_id'])
                ->where('university_id', $data['university_id'])->exists();
            if (!$okCol) {
                return back()->withInput()->withErrors(['college_id' => 'هذه الكلية لا تنتمي للجامعة المحددة.']);
            }
            // تأكد أن التخصص ضمن الكلية
            $okMaj = \App\Models\Major::where('id', $data['major_id'])
                ->where('college_id', $data['college_id'])->exists();
            if (!$okMaj) {
                return back()->withInput()->withErrors(['major_id' => 'هذا التخصص لا ينتمي للكلية المحددة.']);
            }
        }

        // بناء استعلام المستخدمين وفق الهدف
        $usersQuery = \App\Models\User::query()->select('id')->where('status', 'active');

        switch ($data['target_type']) {
            case 'all':
                // الكل
                break;

            case 'users':
                if (empty($data['user_ids'])) {
                    return back()->withInput()->withErrors(['user_ids' => 'اختر مستخدمًا واحدًا على الأقل.']);
                }
                $usersQuery->whereIn('id', $data['user_ids']);
                break;

            case 'university':
                $usersQuery->where('university_id', $data['university_id']);
                break;

            case 'college':
                $usersQuery->where('university_id', $data['university_id'])
                    ->where('college_id',    $data['college_id']);
                break;

            case 'major':
                $usersQuery->where('university_id', $data['university_id'])
                    ->where('college_id',    $data['college_id'])
                    ->where('major_id',      $data['major_id']);
                break;
        }

        // إدراج على دفعات
        $createdCount = 0;
        DB::transaction(function () use ($usersQuery, $data, $dataJson, $type, $now, &$createdCount) {
            $usersQuery->chunkById(1000, function ($users) use ($data, $dataJson, $type, $now, &$createdCount) {
                $rows = [];
                foreach ($users as $u) {
                    $rows[] = [
                        'user_id'     => $u->id,
                        'title'       => $data['title'],
                        'body'        => $data['body'],
                        'target_type' => $data['target_type'],
                        'target_id'   => $this->targetIdFromData($data), // توثيق سياق الهدف
                        'type'        => $type,
                        'data'        => $dataJson ? json_encode($dataJson, JSON_UNESCAPED_UNICODE) : null,
                        'read_at'     => null,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ];
                }
                if ($rows) {
                    DB::table('notifications')->insert($rows);
                    $createdCount += count($rows);
                }
            });
        });

        return redirect()->route('admin.notifications.index')
            ->with('success', "تم إرسال الإشعار إلى {$createdCount} مستخدم.");
    }


    private function targetIdFromData(array $data): ?int
    {
        return $data['major_id'] ?? $data['college_id'] ?? $data['university_id'] ?? null;
    }

    public function show($id)
    {
        $n = Notification::with('user')->findOrFail($id);
        return view('admin.notifications.show', compact('n'));
    }

    public function destroy($id)
    {
        Notification::whereKey($id)->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'تم الحذف.');
    }

    /** -------- AJAX OPTIONS ---------- */

    public function optionsUsers(Request $request)
    {
        // /admin/notifications/options/users?q=&university_id=&college_id=&major_id=&limit=
        $limit = min((int)($request->limit ?? 50), 200);
        $q = User::query()->select('id', 'name', 'email')->where('status', 'active')
            ->when($request->filled('q'), function ($w) use ($request) {
                $t = $request->q;
                $w->where(fn($x) => $x->where('name', 'like', "%{$t}%")->orWhere('email', 'like', "%{$t}%"));
            })
            ->when($request->filled('university_id'), fn($w) => $w->where('university_id', $request->university_id))
            ->when($request->filled('college_id'),    fn($w) => $w->where('college_id',    $request->college_id))
            ->when($request->filled('major_id'),      fn($w) => $w->where('major_id',      $request->major_id))
            ->orderBy('id', 'desc')->limit($limit)->get();

        return response()->json($q->map(fn($u) => [
            'id' => $u->id,
            'text' => "{$u->name} ({$u->email})"
        ]));
    }

    public function optionsUniversities()
    {
        $items = University::query()->select('id', 'name')->orderBy('name')->get();
        return response()->json($items);
    }

    public function optionsColleges(Request $request)
    {
        $request->validate(['university_id' => 'required|exists:universities,id']);
        $items = College::query()->select('id', 'name')->where('university_id', $request->university_id)->orderBy('name')->get();
        return response()->json($items);
    }

    public function optionsMajors(Request $request)
    {
        $request->validate(['college_id' => 'required|exists:colleges,id']);
        $items = Major::query()->select('id', 'name')->where('college_id', $request->college_id)->orderBy('name')->get();
        return response()->json($items);
    }
}
