<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AppContentResource;
use App\Models\AppContent;
use Illuminate\Http\Request;

class AppContentsController extends Controller
{
    /**
     * GET /api/v1/app-contents
     * خيار: ?all=1 لإرجاع كل العناصر (مفعل/مخفي) — مفيد للاختبار
     */
    public function index(Request $request)
    {
        $q = AppContent::query()->ordered();

        if (!$request->boolean('all')) {
            $q->active();
        }

        $items = $q->get();
        return AppContentResource::collection($items);
    }
}
