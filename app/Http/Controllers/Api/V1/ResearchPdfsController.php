<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResearchPdfResource;
use App\Models\ResearchPdf;
use Illuminate\Http\Request;

class ResearchPdfsController extends Controller
{
    public function index(Request $request)
    {
        $items = ResearchPdf::orderBy('order', 'asc')->orderBy('created_at', 'desc')->get();

        return ResearchPdfResource::collection($items);
    }
}
