<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubscriptionByCodeRequest;
use App\Http\Requests\Admin\UpdateSubscriptionRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use App\Services\CodeRedeemer;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $r)
    {
        $q = Subscription::with(['user','plan','activationCode'])->latest();

        // بحث نصي في اسم/إيميل/رقم أكاديمي الطالب + رقم الكود
        if ($s = $r->get('q')) {
            $q->where(function($w) use ($s) {
                $w->whereHas('user', function($wu) use ($s){
                    $wu->where('name','like',"%{$s}%")
                       ->orWhere('email','like',"%{$s}%")
                       ->orWhere('student_number','like',"%{$s}%");
                })->orWhereHas('activationCode', function($wa) use ($s){
                    $wa->where('code','like',"%{$s}%");
                });
            });
        }

        // فلاتر
        $q->status($r->get('status'));
        $q->plan($r->get('plan_id'));
        $q->dateBetween($r->get('from'), $r->get('to'));

        $subs = $q->paginate(15)->withQueryString();

        $users = User::orderBy('name')->get();
        $plans = Plan::orderBy('name')->get();

        return view('admin.subscriptions.index', compact('subs','users','plans'));
    }

    public function create()
    {
        // شاشة "تفعيل يدوي" من اللوحة: اختيار طالب + إدخال كود
        $users = User::orderBy('name')->get();
        return view('admin.subscriptions.create', compact('users'));
    }

    public function store(StoreSubscriptionByCodeRequest $req, CodeRedeemer $redeemer)
    {
        try {
            [$subscription, $msg] = $redeemer->redeemForUser(
                code: $req->string('activation_code')->toString(),
                userId: (int)$req->user_id,
                adminId: auth('admin')->id()
            );

            return redirect()
                ->route('admin.subscriptions.index')
                ->with('success', $msg ?: 'تم تفعيل الكود وإنشاء الاشتراك بنجاح.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Subscription $subscription)
    {
        $subscription->load(['user','plan','activationCode']);
        $plans = Plan::orderBy('name')->get();

        return view('admin.subscriptions.edit', compact('subscription','plans'));
    }

    public function update(UpdateSubscriptionRequest $req, Subscription $subscription)
    {
        $data = $req->validated();

        // لا نسمح بتغيير plan_id إذا الاشتراك أنشئ عبر كود — لأن الخطة مرتبطة بالكود
        unset($data['plan_id']);

        // auto_renew دائماً 0
        $data['auto_renew'] = 0;

        $subscription->update($data);

        return redirect()
            ->route('admin.subscriptions.index')
            ->with('success', 'تم تحديث الاشتراك.');
    }

    public function destroy(Subscription $subscription)
    {
        // حذف ناعم أو حذف نهائي (حسب تصميمك). هنا حذف نهائي.
        $subscription->delete();
        return back()->with('success','تم حذف الاشتراك.');
    }
}
