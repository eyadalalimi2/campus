<?php

namespace App\Http\Controllers\Api\V1\Structure;

use App\Http\Controllers\Controller;
use App\Models\UniversityBranch;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class BranchCollegesController extends Controller
{
    /**
     * Get colleges by branch
     * GET /api/v1/branches/{branch_id}/colleges
     */
    public function byBranch(Request $request, $branch_id)
    {
        $branch = UniversityBranch::with(['colleges' => function($q) {
            $q->select('id', 'name', 'branch_id', 'is_active', 'created_at');
        }])->find($branch_id);

        if (!$branch) {
            return ApiResponse::error('NOT_FOUND', 'الفرع غير موجود.', [], 404);
        }

    return ApiResponse::ok($branch->colleges);
    }
}
