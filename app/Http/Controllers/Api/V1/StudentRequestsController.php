<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Support\ApiResponse;
use App\Http\Resources\Api\V1\StudentRequestResource;
use App\Http\Requests\Api\V1\StudentRequests\StoreRequest;

class StudentRequestsController extends Controller
{
    public function index()
    {
        $u = request()->user();
        $rows = DB::table('student_requests')
            ->where('user_id',$u->id)
            ->orderBy('created_at','desc')->limit(200)->get();

        return ApiResponse::ok(StudentRequestResource::collection($rows));
    }

    public function store(StoreRequest $r)
    {
        $u = request()->user();
        $data = $r->validated();
        $id = DB::table('student_requests')->insertGetId([
            'user_id'      => $u->id,
            'type'         => $data['type'],
            'subject'      => $data['subject'],
            'body'         => $data['body'],
            'status'       => 'open',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $row = DB::table('student_requests')->find($id);
        return ApiResponse::ok(new StudentRequestResource((object)$row), ['created'=>true]);
    }

    public function show($id)
    {
        $u = request()->user();
        $row = DB::table('student_requests')->where('user_id',$u->id)->where('id',$id)->first();
        if (!$row) return ApiResponse::error('NOT_FOUND','الطلب غير موجود.',[],404);
        return ApiResponse::ok(new StudentRequestResource((object)$row));
    }
}
