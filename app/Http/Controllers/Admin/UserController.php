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
use App\Models\PublicCollege;
use App\Models\PublicMajor;
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
        $universities  = University::orderBy('name')->get();
        $branches      = \App\Models\UniversityBranch::with('university:id,name')->orderBy('name')->get();
        $colleges      = College::orderBy('name')->get();
        // جلب التخصصات المرتبطة بالكلية المختارة فقط
        if ($r->filled('college_id')) {
            $majors = Major::where('college_id', $r->college_id)->orderBy('name')->get();
        } else {
            $majors = Major::orderBy('name')->get();
        }
        $countries     = Country::orderBy('name_ar')->get();

        // التصنيف العام (للاستخدام في الفلترة أو العرض إن رغبت)
        $publicColleges = PublicCollege::active()->orderBy('name')->get();
        $publicMajors   = PublicMajor::active()->with('publicCollege')->orderBy('name')->get();

        return view('admin.users.index', compact(
            'users', 'universities', 'colleges', 'majors', 'countries',
            'publicColleges', 'publicMajors'
        ));
    }

    public function create()
    {
        $universities  = University::orderBy('name')->get();
        $branches      = \App\Models\UniversityBranch::with('university:id,name')->orderBy('name')->get();
        $colleges      = College::orderBy('name')->get();
        $majors        = Major::orderBy('name')->get();
        $countries     = Country::orderBy('name_ar')->get();

        // الكليات/التخصصات العامة لإظهارها عند "غير مرتبط"
        $publicColleges = PublicCollege::active()->orderBy('name')->get();
        $publicMajors   = PublicMajor::active()->with('publicCollege')->orderBy('name')->get();

        return view('admin.users.create', compact(
            'universities', 'branches', 'colleges', 'majors', 'countries',
            'publicColleges', 'publicMajors'
        ));
    }

    public function store(StoreUserRequest $req)
    {
    $data = $req->validated();

        // كلمة المرور مطلوبة في Store
        $data['password'] = Hash::make($data['password']);

        // منطق “مرتبط/غير مرتبط بجامعة”
    $linked = ($req->input('is_linked_to_university') === '1');

        if ($linked) {
            // مرتبط: نُبقي الحقول المؤسسية كما هي (حسب التحقق في FormRequest)
            // يمكن جعل student_number/level مطلوبة/اختيارية وفق سياستك.
            // الحقول العامة تُحفظ إذا أرسلت.
        } else {
            // غير مرتبط: نظّف الروابط المؤسسية + الحقول الأكاديمية
            $data['university_id']  = null;
            $data['college_id']     = null;
            $data['major_id']       = null;
            $data['student_number'] = null;
            $data['level']          = null;
            // public_college_id و public_major_id تُحفظ كما أرسلت
        }

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

        // تحقق البريد الإلكتروني
        $data['email_verified_at'] = ($req->get('email_verified') == '1') ? now() : null;
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'تم إضافة الطالب.');
    }

    public function edit(User $user)
    {
        $user->load(['country', 'university', 'college', 'major']);

        $universities  = University::orderBy('name')->get();
        $branches      = \App\Models\UniversityBranch::with('university:id,name')->orderBy('name')->get();
        $colleges      = College::orderBy('name')->get();
        $majors        = Major::orderBy('name')->get();
        $countries     = Country::orderBy('name_ar')->get();

        // الكليات/التخصصات العامة لإظهارها عند "غير مرتبط"
        $publicColleges = PublicCollege::active()->orderBy('name')->get();
        $publicMajors   = PublicMajor::active()->with('publicCollege')->orderBy('name')->get();

        return view('admin.users.edit', compact(
            'user', 'universities', 'branches', 'colleges', 'majors', 'countries',
            'publicColleges', 'publicMajors'
        ));
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
        // تحقق البريد الإلكتروني
        $data['email_verified_at'] = ($req->get('email_verified') == '1') ? now() : null;

        // منطق “مرتبط/غير مرتبط بجامعة”
    $linked = ($req->input('is_linked_to_university') === '1');

        if ($linked) {
            // مرتبط: نُبقي المؤسسي ونُهمل العام إذا لم يُرسل
        } else {
            // غير مرتبط: ننظّف المؤسسي + الحقول الأكاديمية
            $data['university_id']  = null;
            $data['college_id']     = null;
            $data['major_id']       = null;
            $data['student_number'] = null;
            $data['level']          = null;
            // public_college_id و public_major_id تُحفظ كما أرسلت
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
