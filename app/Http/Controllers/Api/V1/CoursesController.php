<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;

class CoursesController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_active',1)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return CourseResource::collection($courses);
    }
}
