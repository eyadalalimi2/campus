<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ClinicalSubject;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ClinicalSubjectResource;

class ClinicalSubjectController extends Controller
{
    public function index()
    {
        $subjects = ClinicalSubject::orderBy('order')->get();
        return ClinicalSubjectResource::collection($subjects);
    }
}
