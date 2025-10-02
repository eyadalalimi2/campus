<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VideoCollection extends ResourceCollection
{
    public $collects = VideoResource::class;

    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'current_page' => $this->currentPage(),
                'from'         => $this->firstItem(),
                'last_page'    => $this->lastPage(),
                'path'         => $request->url(),
                'per_page'     => $this->perPage(),
                'to'           => $this->lastItem(),
                'total'        => $this->total(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last'  => $this->url($this->lastPage()),
                'prev'  => $this->previousPageUrl(),
                'next'  => $this->nextPageUrl(),
            ],
        ];
    }
}
