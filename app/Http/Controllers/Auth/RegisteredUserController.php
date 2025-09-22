<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\Country;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * عرض صفحة التسجيل.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * استقبال ومعالجة طلب التسجيل.
     *
     * ملاحظات:
     * - نعتمد RegisterRequest للتحقق، ويتضمن التحقق الهرمي (جامعة ← فرع ← كلية ← تخصص).
     * - يتم تعيين الدولة إلى اليمن افتراضياً إن لم تُرسل.
     * - جميع الحقول المؤسسية اختيارية؛ عند عدم الإرسال تُحفظ NULL.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // ضمان قيم NULL للحقول الاختيارية عند عدم الإرسال
        $data['phone']          = $data['phone']          ?? null;
        $data['student_number'] = $data['student_number'] ?? null;
        $data['university_id']  = $data['university_id']  ?? null;
        $data['branch_id']      = $data['branch_id']      ?? null;
        $data['college_id']     = $data['college_id']     ?? null;
        $data['major_id']       = $data['major_id']       ?? null;
        $data['level']          = $data['level']          ?? null; // إن كانت موجودة في الفورم

        // الدولة: إن لم تُرسل نضبطها إلى اليمن إن وُجدت بالسجلّات
        if (empty($data['country_id'])) {
            $data['country_id'] =
                Country::where('iso2', 'YE')->value('id')
                ?? Country::where('name_ar', 'اليمن')->value('id')
                ?? Country::where('name', 'Yemen')->value('id')
                ?? null;
        }

        /**
         * ملاحظة حول كلمة المرور:
         * - User::setPasswordAttribute في الموديل سيهشّرها تلقائياً إذا لم تكن مُهشّرة.
         * - لذلك نمرر النص كما هو، والميوتاتور يتكفّل بالباقي.
         */
        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'student_number'    => $data['student_number'] ?? null,
            'country_id'        => $data['country_id'] ?? null,
            'university_id'     => $data['university_id'] ?? null,
            'branch_id'         => $data['branch_id'] ?? null,
            'college_id'        => $data['college_id'] ?? null,
            'major_id'          => $data['major_id'] ?? null,
            'level'             => $data['level'] ?? null,
            'gender'            => $data['gender'] ?? null,   // إن كان الحقل متاحاً في النموذج
            'status'            => User::STATUS_ACTIVE,        // افتراضي
            'password'          => $data['password'],
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
