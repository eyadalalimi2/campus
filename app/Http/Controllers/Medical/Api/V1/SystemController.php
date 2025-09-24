<?php
namespace App\Http\Controllers\Medical\Api\V1;

use Illuminate\Http\Request;
use App\Models\Medical\System;
use App\Models\Medical\SystemSubject;
use App\Http\Resources\Medical\SystemResource;
use App\Http\Resources\Medical\SubjectResource;

class SystemController extends BaseApiController
{
    public function index(Request $r)
    {
        $q = System::query()->where('is_active',1)->orderBy('display_order');
        if ($r->filled('q')) {
            $term = '%'.$r->get('q').'%';
            $q->where(function($w) use ($term){
                $w->where('code','like',$term)->orWhere('name_ar','like',$term)->orWhere('name_en','like',$term);
            });
        }
        [$items,$meta] = $this->paginate($q, 50);
        return $this->ok(SystemResource::collection(collect($items)), $meta);
    }

    public function show($id)
    {
        $sys = System::findOrFail($id);
        // مواد BASIC المرتبطة عبر med_system_subjects
        $subjectIds = SystemSubject::where('system_id',$sys->id)->pluck('subject_id');
        $subjects = \App\Models\Medical\Subject::whereIn('id',$subjectIds)->orderBy('code')->get();
        return $this->ok([
            'system'=> new SystemResource($sys),
            'subjects'=> SubjectResource::collection($subjects),
        ]);
    }
}
