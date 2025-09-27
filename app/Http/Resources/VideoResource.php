<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'thumbnail'    => $this->thumbnail_url,
            'youtube_url'  => $this->youtube_url,
            'published_at' => optional($this->published_at)->toDateTimeString(),

            'doctor'  => $this->whenLoaded('doctor', fn()=>[
                'id' => $this->doctor->id,
                'name' => $this->doctor->name,
                'slug' => $this->doctor->slug,
            ]),

            'subject' => $this->whenLoaded('subject', fn()=>[
                'id' => $this->subject->id,
                'name' => $this->subject->name,
                'slug' => $this->subject->slug,
            ]),

            'topic'   => $this->whenLoaded('topic', fn()=>[
                'id' => $this->topic->id,
                'title' => $this->topic->title,
                'slug' => $this->topic->slug,
            ]),
        ];
    }
}
