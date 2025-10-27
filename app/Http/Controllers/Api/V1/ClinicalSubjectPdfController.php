<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ClinicalSubject;
use App\Http\Resources\ClinicalSubjectPdfResource;

class ClinicalSubjectPdfController extends Controller
{
    // GET /api/clinical-subjects/{clinical_subject}/pdfs
    public function index(ClinicalSubject $clinical_subject)
    {
        $pdfs = $clinical_subject->pdfs()->orderBy('order')->get();
        return ClinicalSubjectPdfResource::collection($pdfs);
    }
}
