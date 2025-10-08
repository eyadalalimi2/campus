<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AppFeatureResource;
use App\Models\AppFeature;

class AppFeaturesController extends Controller
{
    /**
     * GET /api/v1/app-features
     * عام (بدون توكن)، يعيد المفعّلة فقط بالترتيب.
     */
    public function index()
    {
        $rows = AppFeature::active()->ordered()->get();
        return AppFeatureResource::collection($rows);
    }
}
