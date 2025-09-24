<?php
namespace App\Http\Controllers\Medical\Api\V1;

use Illuminate\Http\Request;
use App\Models\Medical\Doctor;
use App\Models\Medical\DoctorSubject;
use App\Http\Resources\Medical\DoctorResource;
use App\Http\Resources\Medical\DoctorWithSystemsResource;

class DoctorController extends BaseApiController
{
    public function index(Request $r)
    {
        $q = Doctor::query()->orderByDesc('verified')->orderByDesc('score')->orderBy('name');
        if ($r->filled('subject_id')) {
            $docIds = DoctorSubject::where('subject_id',$r->get('subject_id'))->pluck('doctor_id');
            $q->whereIn('id',$docIds);
        }
        [$items,$meta] = $this->paginate($q, 50);
        return $this->ok(DoctorResource::collection(collect($items)), $meta);
    }

    public function bySubject($subjectId)
    {
        $rows = DoctorSubject::with(['doctor','subject','systems'])
            ->where('subject_id',$subjectId)
            ->orderBy('priority')->get();

        return $this->ok(DoctorWithSystemsResource::collection($rows));
    }
}
