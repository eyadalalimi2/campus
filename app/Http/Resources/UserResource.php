<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * إلغاء تغليف الاستجابة داخل "data"
     */
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'student_number'     => $this->student_number,
            'name'               => $this->name,
            'email'              => $this->email,
            'phone'              => $this->phone,
            'country'            => $this->country,

            // الحقل الفعلي في قاعدة البيانات
            'profile_photo_path' => $this->profile_photo_path,

            // رابط مباشر للصورة (اختياري للاستهلاك في التطبيق)
            'profile_photo_url'  => $this->profile_photo_path
                ? Storage::disk('public')->url($this->profile_photo_path)
                : null,

            'university_id'      => $this->university_id,
            'college_id'         => $this->college_id,
            'major_id'           => $this->major_id,
            'level'              => $this->level,
            'gender'             => $this->gender,
            'status'             => $this->status,

            // أبقيناه نصاً كما يعود من قاعدة البيانات
            'email_verified_at'  => $this->email_verified_at,

            // ISO8601 (كما في أمثلتك)
            'created_at'         => $this->created_at ? $this->created_at->toJSON() : null,
            'updated_at'         => $this->updated_at ? $this->updated_at->toJSON() : null,
        ];
    }
}
