<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePlanFeatureRequest;
use App\Http\Requests\Admin\UpdatePlanFeatureRequest;
use App\Models\Plan;
use App\Models\PlanFeature;

class PlanFeatureController extends Controller
{
    public function index(Plan $plan)
    {
        $features = $plan->features()->orderBy('feature_key')->paginate(50);
        return view('admin.plan_features.index', compact('plan','features'));
    }

    public function create(Plan $plan)
    {
        $feature = new PlanFeature();
        return view('admin.plan_features.create', compact('plan','feature'));
    }

    public function store(StorePlanFeatureRequest $request, Plan $plan)
    {
        $data = $request->validated();
        $data['plan_id'] = $plan->id;
        PlanFeature::create($data);

        return redirect()
            ->route('admin.plan_features.index', ['plan'=>$plan->id])
            ->with('success','تمت إضافة الميزة.');
    }

    public function edit(Plan $plan, PlanFeature $feature)
    {
        // تأكيد الانتماء
        abort_if($feature->plan_id !== $plan->id, 404);
        return view('admin.plan_features.edit', compact('plan','feature'));
    }

    public function update(UpdatePlanFeatureRequest $request, Plan $plan, PlanFeature $feature)
    {
        abort_if($feature->plan_id !== $plan->id, 404);

        $feature->update($request->validated());

        return redirect()
            ->route('admin.plan_features.index', ['plan'=>$plan->id])
            ->with('success','تم التحديث.');
    }

    public function destroy(Plan $plan, PlanFeature $feature)
    {
        abort_if($feature->plan_id !== $plan->id, 404);

        $feature->delete();

        return back()->with('success','تم الحذف.');
    }
}
