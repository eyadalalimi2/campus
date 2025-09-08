<?php

namespace App\Http\Controllers\Api\V1\Plans;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FeatureResource;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Support\ApiResponse;

final class FeaturesController extends Controller
{
    /**
     * GET /api/v1/plans/{id}/features
     */
    public function byPlan(int $id)
    {
        $plan = Plan::query()->find($id);
        if (!$plan) return ApiResponse::error('NOT_FOUND','الخطة غير موجودة.',[],404);

        $features = PlanFeature::query()
            ->select(['id','plan_id','feature_key','feature_value','created_at'])
            ->where('plan_id', $plan->id)
            ->orderBy('feature_key')
            ->get();

        return ApiResponse::ok(FeatureResource::collection($features), ['count'=>$features->count()]);
    }
}
