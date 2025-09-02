<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $r)
    {
        $q = User::with(['university', 'college', 'major'])->orderBy('name');

        // بحث: الاسم + الهاتف + الرقم الأكاديمي + الدولة
        if ($s = $r->get('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%$s%")
                    ->orWhere('phone', 'like', "%$s%")
                    ->orWhere('student_number', 'like', "%$s%")
                    ->orWhere('country', 'like', "%$s%");
            });
        }

        // فلاتر
        if ($r->filled('university_id')) $q->where('university_id', $r->university_id);
        if ($r->filled('college_id'))    $q->where('college_id', $r->college_id);
        if ($r->filled('major_id'))      $q->where('major_id', $r->major_id);
        if ($r->filled('gender'))        $q->where('gender', $r->gender);
        if ($r->filled('status'))        $q->where('status', $r->status);
        if ($r->filled('level'))         $q->where('level', $r->level);
        if ($r->filled('country'))       $q->where('country', $r->country); 
        $users = $q->paginate(15)->withQueryString();

        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'universities', 'colleges', 'majors'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        return view('admin.users.create', compact('universities', 'colleges', 'majors'));
    }

    public function store(UserRequest $req)
    {
        $data = $req->validated();

        // كلمة المرور
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // الدولة: افتراضي "اليمن" إن لم تُرسل
        if (empty($data['country'])) {
            $data['country'] = 'اليمن';
        }

        // رفع صورة
        if ($req->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $req->file('profile_photo')->store('profiles', 'public');
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'تم إضافة الطالب.');
    }

    public function edit(User $user)
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'universities', 'colleges', 'majors'));
    }

    public function update(UserRequest $req, User $user)
    {
        $data = $req->validated();

        // كلمة المرور اختيارية عند التحديث
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // الدولة: ابقِ القيمة المرسلة وإلا استخدم "اليمن" إذا كانت فارغة/غير موجودة
        if (!array_key_exists('country', $data) || $data['country'] === null || $data['country'] === '') {
            $data['country'] = 'اليمن';
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
        $user->delete();
        return back()->with('success', 'تم حذف الطالب.');
    }
    public function show(User $user)
    {
        $user->load(['university', 'college', 'major']);
        return view('admin.users.show', compact('user'));
    }
}
