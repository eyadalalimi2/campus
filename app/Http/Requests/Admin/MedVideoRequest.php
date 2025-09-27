<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedVideoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $video = $this->route('video');
        // فريد مركّب: youtube_url + subject_id + topic_id
        return [
            'doctor_id' => ['required','integer','exists:med_doctors,id'],
            'subject_id' => ['required','integer','exists:med_subjects,id'],
            'topic_id' => ['nullable','integer','exists:med_topics,id'],
            'title' => ['required','string','max:255'],
            'thumbnail' => ['nullable','image','mimes:png,jpg,jpeg,webp','max:2048'],
            'thumbnail_url' => ['nullable','url'],
            'youtube_url' => [
                'required','url','max:500',
                function($attr,$value,$fail) {
                    $subjectId = request('subject_id');
                    $topicId = request('topic_id'); // قد يكون null
                    $exists = \DB::table('med_videos')
                        ->where('youtube_url',$value)
                        ->where('subject_id',$subjectId)
                        ->where(function($q) use($topicId){
                            if (is_null($topicId)) $q->whereNull('topic_id');
                            else $q->where('topic_id',$topicId);
                        })
                        ->when($this->route('video'), fn($q)=>$q->where('id','!=',$this->route('video')->id))
                        ->exists();
                    if ($exists) $fail('هذا الفيديو موجود مسبقاً لنفس المادة/الموضوع.');
                }
            ],
            'order_index' => ['nullable','integer','min:0'],
            'status' => ['required','in:draft,published'],
            'published_at' => ['nullable','date'],
        ];
    }
}
