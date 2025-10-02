<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceFileResource;
use App\Models\{MedResource, MedSubject, MedTopic};

class MedResourceController extends Controller
{
    public function index()
    {
        $perPage = (int) request('per_page', 20);

        $resources = MedResource::with(['category','subject','topic'])
            ->when(request('subject_id'), fn($q,$v) => $q->where('subject_id', $v))
            ->when(request('topic_id'),   fn($q,$v) => $q->where('topic_id',   $v))
            ->when(request('category'),   fn($q,$v) => $q->whereHas('category', fn($qr) => $qr->where('code', $v)))
            ->when(request('q'),          fn($q,$v) => $q->where('title', 'like', "%{$v}%"))
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate($perPage);

        return response()->json([
            'data'  => ResourceFileResource::collection($resources)->resolve(), // <-- مهم
            'links' => [
                'first' => $resources->url(1),
                'last'  => $resources->url($resources->lastPage()),
                'prev'  => $resources->previousPageUrl(),
                'next'  => $resources->nextPageUrl(),
            ],
            'meta'  => [
                'current_page' => $resources->currentPage(),
                'from'         => $resources->firstItem(),
                'last_page'    => $resources->lastPage(),
                'path'         => $resources->path(),
                'per_page'     => $resources->perPage(),
                'to'           => $resources->lastItem(),
                'total'        => $resources->total(),
                'links'        => $resources->linkCollection()->toArray(),
            ],
        ]);
    }

    public function bySubject(MedSubject $subject)
    {
        $perPage = (int) request('per_page', 20);

        $resources = $subject->resources()
            ->with(['category','topic'])
            ->when(request('category'), fn($q,$v) => $q->whereHas('category', fn($qr) => $qr->where('code', $v)))
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate($perPage);

        return response()->json([
            'data'  => ResourceFileResource::collection($resources)->resolve(),
            'links' => [
                'first' => $resources->url(1),
                'last'  => $resources->url($resources->lastPage()),
                'prev'  => $resources->previousPageUrl(),
                'next'  => $resources->nextPageUrl(),
            ],
            'meta'  => [
                'current_page' => $resources->currentPage(),
                'from'         => $resources->firstItem(),
                'last_page'    => $resources->lastPage(),
                'path'         => $resources->path(),
                'per_page'     => $resources->perPage(),
                'to'           => $resources->lastItem(),
                'total'        => $resources->total(),
                'links'        => $resources->linkCollection()->toArray(),
            ],
        ]);
    }

    public function byTopic(MedTopic $topic)
    {
        $perPage = (int) request('per_page', 20);

        $resources = $topic->resources()
            ->with(['category','subject'])
            ->when(request('category'), fn($q,$v) => $q->whereHas('category', fn($qr) => $qr->where('code', $v)))
            ->where('status','published')
            ->orderBy('order_index')
            ->paginate($perPage);

        return response()->json([
            'data'  => ResourceFileResource::collection($resources)->resolve(),
            'links' => [
                'first' => $resources->url(1),
                'last'  => $resources->url($resources->lastPage()),
                'prev'  => $resources->previousPageUrl(),
                'next'  => $resources->nextPageUrl(),
            ],
            'meta'  => [
                'current_page' => $resources->currentPage(),
                'from'         => $resources->firstItem(),
                'last_page'    => $resources->lastPage(),
                'path'         => $resources->path(),
                'per_page'     => $resources->perPage(),
                'to'           => $resources->lastItem(),
                'total'        => $resources->total(),
                'links'        => $resources->linkCollection()->toArray(),
            ],
        ]);
    }
}
