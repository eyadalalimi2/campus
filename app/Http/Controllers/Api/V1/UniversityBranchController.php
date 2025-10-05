<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UniversityBranch;
use Illuminate\Http\Request;

class UniversityBranchController extends Controller
{
    /**
     * جلب فروع جامعة معينة
     */
    public function byUniversity($id)
    {
        $branches = UniversityBranch::where('university_id', $id)->orderBy('name')->get();
        return \App\Http\Resources\Api\V1\UniversityBranchResource::collection($branches);
    }

    /**
     * قائمة جميع الفروع
     */
    public function index(Request $request)
    {
        $query = UniversityBranch::query();
        if ($request->has('university_id')) {
            $query->where('university_id', $request->university_id);
        }
        $branches = $query->orderBy('name')->get();
        return \App\Http\Resources\Api\V1\UniversityBranchResource::collection($branches);
    }
}
