<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\{MedVideo, MedDoctor, MedTopic, MedSubject};

class MedVideoController extends Controller
{
    public function index()
    {
        $default = (int) config('api.pagination.default', 20);
        $max     = (int) config('api.pagination.max', 50);
        $perPage = (int) request('per_page', $default);
        $perPage = max(1, min($perPage, $max));

        $videos = MedVideo::with(['doctor','subject','topic'])
            ->when(request('subject_id'), fn($q,$v) => $q->where('subject_id',$v))
            ->when(request('topic_id'),   fn($q,$v) => $q->where('topic_id',$v))
            ->when(request('doctor_id'),  fn($q,$v) => $q->where('doctor_id',$v))
            ->when(request('q'),          fn($q,$v) => $q->where('title','like',"%{$v}%"))
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate($perPage);

        // ملاحظة مهمة: بدون additional وبدون أي التفاف/تجميع إضافي
        return VideoResource::collection($videos);
    }

    public function byDoctor(MedDoctor $doctor)
    {
        $default = (int) config('api.pagination.default', 20);
        $max     = (int) config('api.pagination.max', 50);
        $perPage = (int) request('per_page', $default);
        $perPage = max(1, min($perPage, $max));

        $videos = MedVideo::with(['subject','topic'])
            ->where('doctor_id', $doctor->id)
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate($perPage);

        return VideoResource::collection($videos);
    }

    public function byTopic(MedTopic $topic)
    {
        $default = (int) config('api.pagination.default', 20);
        $max     = (int) config('api.pagination.max', 50);
        $perPage = (int) request('per_page', $default);
        $perPage = max(1, min($perPage, $max));

        $videos = MedVideo::with(['doctor','subject'])
            ->where('topic_id', $topic->id)
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate($perPage);

        return VideoResource::collection($videos);
    }

    public function bySubject(MedSubject $subject)
    {
        $default = (int) config('api.pagination.default', 20);
        $max     = (int) config('api.pagination.max', 50);
        $perPage = (int) request('per_page', $default);
        $perPage = max(1, min($perPage, $max));

        $videos = MedVideo::with(['doctor','topic'])
            ->where('subject_id', $subject->id)
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate($perPage);

        return VideoResource::collection($videos);
    }
}
