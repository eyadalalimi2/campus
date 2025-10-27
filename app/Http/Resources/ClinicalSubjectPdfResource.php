<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicalSubjectPdfResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'file' => $this->file ? asset('storage/' . $this->file) : null,
            'order' => $this->order,
            'clinical_subject_id' => $this->clinical_subject_id,
        ];
    }
}
