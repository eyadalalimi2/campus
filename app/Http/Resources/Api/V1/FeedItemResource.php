<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * عنصر موجز موحّد (Asset أو Content).
 * يُفترض أن الخدمة تُمرّر حقل 'kind' بقيم: 'asset' | 'content'
 */
final class FeedItemResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        $kind = (string) data_get($this->resource, 'kind', 'asset');

        $base = [
            'kind'         => $kind,
            'id'           => (int) data_get($this->resource, 'id'),
            'title'        => data_get($this->resource, 'title'),
            'description'  => data_get($this->resource, 'description'),
            'published_at' => data_get($this->resource, 'published_at') ? (string) data_get($this->resource, 'published_at') : null,
            'material_id'  => data_get($this->resource, 'material_id') !== null ? (int) data_get($this->resource, 'material_id') : null,
            'media'        => [
                'video_url'   => data_get($this->resource, 'media.video_url'),
                'file_path'   => data_get($this->resource, 'media.file_path'),
                'external_url'=> data_get($this->resource, 'media.external_url'),
                'source_url'  => data_get($this->resource, 'media.source_url'),
            ],
        ];

        if ($kind === 'asset') {
            $base += [
                'category'      => data_get($this->resource, 'category'),
                'discipline_id' => data_get($this->resource, 'discipline_id') !== null ? (int) data_get($this->resource, 'discipline_id') : null,
                'program_id'    => data_get($this->resource, 'program_id') !== null ? (int) data_get($this->resource, 'program_id') : null,
            ];
        } else { // content
            $base += [
                'type'          => data_get($this->resource, 'type'),
                'university_id' => data_get($this->resource, 'university_id') !== null ? (int) data_get($this->resource, 'university_id') : null,
                'college_id'    => data_get($this->resource, 'college_id') !== null ? (int) data_get($this->resource, 'college_id') : null,
                'major_id'      => data_get($this->resource, 'major_id') !== null ? (int) data_get($this->resource, 'major_id') : null,
            ];
        }

        return $base;
    }
}
