<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        $courses = $query->orderBy('sort_order')->get();
        return \App\Http\Resources\Api\V1\CourseResource::collection($courses);
    }
}
