<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TopicResource;
use App\Models\MedSubject;

class MedTopicController extends Controller
{
    public function bySubject(MedSubject $subject)
    {
        $topics = $subject->topics()
            ->where('status', 'published')
            ->orderBy('order_index')
            ->get();

        return TopicResource::collection($topics);
    }
}
