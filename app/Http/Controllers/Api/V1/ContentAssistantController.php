<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContentAssistant;
use App\Http\Resources\ContentAssistantResource;
use Illuminate\Http\Request;

class ContentAssistantController extends Controller
{
    public function index(Request $request)
    {
        $rows = ContentAssistant::query()
            ->active()
            ->orderDefault()
            ->get();

        return ContentAssistantResource::collection($rows);
    }
}
