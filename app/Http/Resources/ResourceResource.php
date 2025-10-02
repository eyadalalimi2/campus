<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'file_url'    => $this->file_url,   // تأكد الحقل الصحيح في الموديل
            'file_size'   => $this->file_size,
            'pages'       => $this->pages,

            'category' => $this->whenLoaded('category', fn()=>[
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'code' => $this->category->code,
            ]),

            'subject' => $this->whenLoaded('subject', fn()=>[
                'id'   => $this->subject->id,
                'name' => $this->subject->name,
                'slug' => $this->subject->slug,
            ]),

            'topic' => $this->whenLoaded('topic', fn()=>[
                'id'    => $this->topic->id,
                'title' => $this->topic->title,
                'slug'  => $this->topic->slug,
            ]),
        ];
    }
}
