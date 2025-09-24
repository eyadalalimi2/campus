<?php
namespace App\Http\Controllers\Medical\Api\V1;

use Illuminate\Http\Request;
use App\Models\Medical\QuestionBank;
use App\Models\Medical\Question;
use App\Http\Resources\Medical\QuestionBankResource;
use App\Http\Resources\Medical\QuestionResource;

class QuestionBankController extends BaseApiController
{
    public function index(Request $r)
    {
        $q = QuestionBank::query()->with('resource');
        if ($r->filled('subject_id')) {
            $q->whereHas('resource', fn($w)=>$w->where('subject_id',$r->get('subject_id')));
        }
        if ($r->filled('system_id')) {
            $q->whereHas('resource', fn($w)=>$w->where('system_id',$r->get('system_id')));
        }

        [$items,$meta] = $this->paginate($q->orderBy('id'), 20);
        return $this->ok(QuestionBankResource::collection(collect($items)), $meta);
    }

    public function show($id)
    {
        $bank = QuestionBank::with('resource')->findOrFail($id);
        return $this->ok(new QuestionBankResource($bank));
    }

    public function questions($id)
    {
        $q = Question::with(['options','stats'])->where('resource_id', function($sub) use ($id){
            $sub->select('resource_id')->from('med_question_banks')->where('id',$id)->limit(1);
        });

        if (request()->filled('difficulty')) $q->where('difficulty', request('difficulty'));
        if (request()->filled('type'))       $q->where('type', request('type'));

        [$items,$meta] = $this->paginate($q->orderBy('id'), 30);
        return $this->ok(QuestionResource::collection(collect($items)), $meta);
    }
}
