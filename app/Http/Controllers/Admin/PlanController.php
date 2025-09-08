<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePlanRequest;
use App\Http\Requests\Admin\UpdatePlanRequest;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('features')->orderBy('name')->paginate(15);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        $plan = new Plan();
        return view('admin.plans.create', compact('plan'));
    }

    public function store(StorePlanRequest $request)
    {
        $plan = Plan::create($request->validated());
        return redirect()->route('admin.plans.index')->with('success','تم إنشاء الخطة بنجاح.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->update($request->validated());
        return redirect()->route('admin.plans.index')->with('success','تم تحديث الخطة بنجاح.');
    }

    public function destroy(Plan $plan)
    {
        // ملاحظة: يمكنك منع الحذف إن كانت الخطة مستخدمة في اشتراكات/أكواد
        $plan->delete();
        return back()->with('success','تم حذف الخطة.');
    }
}
