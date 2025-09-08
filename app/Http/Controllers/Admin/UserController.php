<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $r)
    {
        $q = User::query()
            ->with(['country', 'university', 'college', 'major'])
            ->orderBy('name');

        // بحث حر: الاسم/البريد/الهاتف/الرقم الأكاديمي + اسم الدولة (عربي)
        if ($s = $r->string('q')->toString()) {
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                    ->orWhere('student_number', 'like', "%{$s}%")
                    ->orWhereHas('country', function ($cw) use ($s) {
                        $cw->where('name_ar', 'like', "%{$s}%");
                    });
            });
        }

        // فلاتر دقيقة
        if ($r->filled('university_id')) $q->where('university_id', (int) $r->university_id);
        if ($r->filled('college_id'))    $q->where('college_id', (int) $r->college_id);
        if ($r->filled('major_id'))      $q->where('major_id', (int) $r->major_id);
        if ($r->filled('country_id'))    $q->where('country_id', (int) $r->country_id);
        if ($r->filled('gender'))        $q->where('gender', $r->gender);
        if ($r->filled('status'))        $q->where('status', $r->status);
        if ($r->filled('level'))         $q->where('level', (int) $r->level);

        $users = $q->paginate(15)->withQueryString();

        // مصادر القوائم
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $countries    = Country::orderBy('name_ar')->get();

        return view('admin.users.index', compact('users', 'universities', 'colleges', 'majors', 'countries'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $countries    = Country::orderBy('name_ar')->get();

        return view('admin.users.create', compact('universities', 'colleges', 'majors', 'countries'));
    }

    public function store(StoreUserRequest $req)
    {
        $data = $req->validated();

        // كلمة المرور مطلوبة في Store
        $data['password'] = Hash::make($data['password']);

        // country_id: إن لم يُرسل، استخدم اليمن كافتراضي (إن وجد)
        if (empty($data['country_id'])) {
            $data['country_id'] = Country::where('iso2', 'YE')->value('id')
                ?? Country::where('name_ar', 'اليمن')->value('id')
                ?? 1; // احتياط
        }

        // رفع صورة البروفايل
        if ($req->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $req->file('profile_photo')->store('profiles', 'public');
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'تم إضافة الطالب.');
    }

    public function edit(User $user)
    {
        $user->load(['country', 'university', 'college', 'major']);

        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $countries    = Country::orderBy('name_ar')->get();

        return view('admin.users.edit', compact('user', 'universities', 'colleges', 'majors', 'countries'));
    }

    public function update(UpdateUserRequest $req, User $user)
    {
        $data = $req->validated();

        // كلمة المرور اختيارية في Update
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // country_id افتراضي لليمن إن لم يُرسل
        if (empty($data['country_id'])) {
            $data['country_id'] = $user->country_id
                ?? Country::where('iso2', 'YE')->value('id')
                ?? Country::where('name_ar', 'اليمن')->value('id')
                ?? 1;
        }

        // تحديث صورة البروفايل
        if ($req->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $data['profile_photo_path'] = $req->file('profile_photo')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'تم تحديث بيانات الطالب.');
    }

    public function destroy(User $user)
    {
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // تنظيف الجلسات لتفادي تعارض FK
        DB::table('sessions')->where('user_id', $user->id)->delete();

        $user->delete();
        return back()->with('success', 'تم حذف الطالب.');
    }

    public function show(User $user)
    {
        $user->load(['country', 'university', 'college', 'major']);
        return view('admin.users.show', compact('user'));
    }
}
