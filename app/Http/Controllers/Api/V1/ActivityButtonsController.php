<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ActivityButton;
use App\Http\Resources\ActivityButtonResource;
use App\Http\Resources\ActivityVideoResource;
use Illuminate\Http\Request;

class ActivityButtonsController extends Controller
{
    // GET /api/v1/activity-buttons
    public function index()
    {
        $buttons = ActivityButton::orderBy('order')->get();
        return ActivityButtonResource::collection($buttons);
    }

    // GET /api/v1/activity-buttons/{button}/videos
    public function videos(ActivityButton $button)
    {
        $videos = $button->videos()->orderBy('order')->get();
        return ActivityVideoResource::collection($videos);
    }
}
