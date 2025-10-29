<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicalTipResource;
use App\Models\MedicalTip;
use Illuminate\Http\Request;

class MedicalTipsController extends Controller
{
    /**
     * Return a list of medical tips for public consumption (Android app).
     */
    public function index(Request $request)
    {
        // Order by the explicit 'order' field (ascending), then newest
        $tips = MedicalTip::orderBy('order', 'asc')->orderBy('created_at', 'desc')->get();

        return MedicalTipResource::collection($tips);
    }
}
