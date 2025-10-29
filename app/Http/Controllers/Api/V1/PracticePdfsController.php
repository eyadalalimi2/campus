<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PracticePdfResource;
use App\Models\PracticePdf;
use Illuminate\Http\Request;

class PracticePdfsController extends Controller
{
    /**
     * Return list of practice exam PDFs for mobile app.
     */
    public function index(Request $request)
    {
        $pdfs = PracticePdf::orderBy('order', 'asc')->orderBy('created_at', 'desc')->get();

        return PracticePdfResource::collection($pdfs);
    }
}
