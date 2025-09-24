<?php
namespace App\Http\Controllers\Medical\Api\V1;

use Illuminate\Http\Request;
use App\Models\Medical\Resource;
use App\Http\Resources\Medical\ResourceResource;

class ResourceController extends BaseApiController
{
    public function index(Request $r)
    {
        $q = Resource::query()->where('status','PUBLISHED');

        // فلاتر عامة
        if ($r->filled('type'))      $q->where('type', $r->get('type'));
        if ($r->filled('track'))     $q->where('track', $r->get('track'));
        if ($r->filled('subject_id'))$q->where('subject_id', $r->get('subject_id'));
        if ($r->filled('system_id')) $q->where('system_id', $r->get('system_id'));
        if ($r->filled('doctor_id')) $q->where('doctor_id', $r->get('doctor_id'));
        if ($r->filled('year_from')) $q->where('year','>=',(int)$r->get('year_from'));
        if ($r->filled('year_to'))   $q->where('year','<=',(int)$r->get('year_to'));
        if ($r->filled('lang'))      $q->where('language',$r->get('lang'));
        if ($r->filled('q')) {
            $term = '%'.$r->get('q').'%';
            $q->where(function($w) use ($term){
                $w->where('title','like',$term)->orWhere('title_en','like',$term)->orWhere('description','like',$term);
            });
        }

        // الظهور
        if ($r->get('visibility','PUBLIC') === 'PUBLIC') {
            $q->where('visibility','PUBLIC');
        } else {
            $q->where('visibility','RESTRICTED');
            if ($r->filled('university_id')) {
                $q->whereHas('universities', fn($w)=>$w->where('med_universities.id',$r->get('university_id')));
            }
        }

        $q->orderByDesc('rating')->orderByDesc('popularity')->orderBy('id');

        $q->with(['subject','system','doctor']);
        if ($r->boolean('with_files'))      $q->with('files');
        if ($r->boolean('with_youtube'))    $q->with('youtubeMeta');

        [$items,$meta] = $this->paginate($q, (int)$r->get('per_page',20));
        return $this->ok(ResourceResource::collection(collect($items)), $meta);
    }

    public function show($id)
    {
        $res = Resource::with(['subject','system','doctor','files','youtubeMeta'])->findOrFail($id);
        return $this->ok(new ResourceResource($res));
    }
}
