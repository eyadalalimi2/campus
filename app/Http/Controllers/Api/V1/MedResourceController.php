<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceFileResource;
use App\Models\{MedResource, MedSubject, MedTopic};

class MedResourceController extends Controller
{
    public function index()
    {
        $resources = MedResource::with(['category','subject','topic'])
            ->when(request('subject_id'), fn($q,$v) => $q->where('subject_id',$v))
            ->when(request('topic_id'), fn($q,$v) => $q->where('topic_id',$v))
            ->when(request('category'), fn($q,$v) => $q->whereHas('category', fn($qr)=>$qr->where('code',$v)))
            ->when(request('q'), fn($q,$v) => $q->where('title','like',"%{$v}%"))
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate(request('per_page',20));

        return ResourceFileResource::collection($resources)->additional([
            'meta' => [
                'current_page' => $resources->currentPage(),
                'per_page'     => $resources->perPage(),
                'total'        => $resources->total(),
                'last_page'    => $resources->lastPage(),
            ]
        ]);
    }

    public function bySubject(MedSubject $subject)
    {
        $resources = $subject->resources()
            ->with(['category','topic'])
            ->when(request('category'), fn($q,$v) => $q->whereHas('category', fn($qr)=>$qr->where('code',$v)))
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate(request('per_page',20));

        return ResourceFileResource::collection($resources)->additional([
            'meta' => [
                'current_page' => $resources->currentPage(),
                'per_page'     => $resources->perPage(),
                'total'        => $resources->total(),
                'last_page'    => $resources->lastPage(),
            ]
        ]);
    }

    public function byTopic(MedTopic $topic)
    {
        $resources = $topic->resources()
            ->with(['category','subject'])
            ->when(request('category'), fn($q,$v) => $q->whereHas('category', fn($qr)=>$qr->where('code',$v)))
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate(request('per_page',20));

        return ResourceFileResource::collection($resources)->additional([
            'meta' => [
                'current_page' => $resources->currentPage(),
                'per_page'     => $resources->perPage(),
                'total'        => $resources->total(),
                'last_page'    => $resources->lastPage(),
            ]
        ]);
    }
}
