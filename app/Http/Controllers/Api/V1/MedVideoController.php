<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\{MedVideo, MedDoctor, MedTopic, MedSubject};

class MedVideoController extends Controller
{
    public function index()
    {
        $videos = MedVideo::with(['doctor','subject','topic'])
            ->when(request('subject_id'), fn($q,$v) => $q->where('subject_id',$v))
            ->when(request('topic_id'), fn($q,$v) => $q->where('topic_id',$v))
            ->when(request('doctor_id'), fn($q,$v) => $q->where('doctor_id',$v))
            ->when(request('q'), fn($q,$v) => $q->where('title','like',"%{$v}%"))
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate(request('per_page',20));

        return VideoResource::collection($videos)->additional([
            'meta' => [
                'current_page' => $videos->currentPage(),
                'per_page'     => $videos->perPage(),
                'total'        => $videos->total(),
                'last_page'    => $videos->lastPage(),
            ]
        ]);
    }

    public function byDoctor(MedDoctor $doctor)
    {
        $videos = MedVideo::with(['subject','topic'])
            ->where('doctor_id',$doctor->id)
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate(request('per_page',20));

        return VideoResource::collection($videos)->additional([
            'meta' => [
                'current_page' => $videos->currentPage(),
                'per_page'     => $videos->perPage(),
                'total'        => $videos->total(),
                'last_page'    => $videos->lastPage(),
            ]
        ]);
    }

    public function byTopic(MedTopic $topic)
    {
        $videos = MedVideo::with(['doctor','subject'])
            ->where('topic_id',$topic->id)
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate(request('per_page',20));

        return VideoResource::collection($videos)->additional([
            'meta' => [
                'current_page' => $videos->currentPage(),
                'per_page'     => $videos->perPage(),
                'total'        => $videos->total(),
                'last_page'    => $videos->lastPage(),
            ]
        ]);
    }

    public function bySubject(MedSubject $subject)
    {
        $videos = MedVideo::with(['doctor','topic'])
            ->where('subject_id',$subject->id)
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate(request('per_page',20));

        return VideoResource::collection($videos)->additional([
            'meta' => [
                'current_page' => $videos->currentPage(),
                'per_page'     => $videos->perPage(),
                'total'        => $videos->total(),
                'last_page'    => $videos->lastPage(),
            ]
        ]);
    }
}
