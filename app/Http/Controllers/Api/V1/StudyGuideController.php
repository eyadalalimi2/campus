<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\StudyGuideResource;
use App\Models\StudyGuide;
use Illuminate\Http\Request;

class StudyGuideController extends Controller
{
    // GET /api/v1/study-guides
    public function index(Request $request)
    {
        $items = StudyGuide::query()->latest()->get();
        return StudyGuideResource::collection($items);
    }

    // GET /api/v1/study-guides/{study_guide}
    public function show(StudyGuide $study_guide)
    {
        return new StudyGuideResource($study_guide);
    }
}
