<?php
namespace App\Http\Controllers\Medical\Api\V1;

use Illuminate\Http\Request;
use App\Models\Medical\Subject;
use App\Http\Resources\Medical\SubjectResource;

class SubjectController extends BaseApiController
{
    public function index(Request $r)
    {
        $q = Subject::query()->where('is_active',1)->orderBy('code');
        if ($r->filled('track')) {
            $q->where('track_scope',$r->get('track'));
        }
        [$items,$meta] = $this->paginate($q, 100);
        return $this->ok(SubjectResource::collection(collect($items)), $meta);
    }

    public function show($id)
    {
        $s = Subject::findOrFail($id);
        return $this->ok(new SubjectResource($s));
    }
}
