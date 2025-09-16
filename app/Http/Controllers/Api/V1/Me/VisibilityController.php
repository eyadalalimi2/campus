<?php
namespace App\Http\Controllers\Api\V1\Me;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class VisibilityController extends Controller
{
    public function show()
    {
        $u = request()->user();
        $row = DB::table('users')->where('id',$u->id)->first();
        $linked = (bool)$row->university_id;
        return ApiResponse::ok([
            'linked_to_university' => $linked,
            'allowed_sources' => $linked ? ['assets','contents'] : ['assets'],
            'scope'=>[
                'university_id'=>$row->university_id,'college_id'=>$row->college_id,'major_id'=>$row->major_id
            ]
        ]);
    }

    public function update(Request $r)
    {
        $u = request()->user();
        $data = $r->validate([
            'university_id' => ['nullable','integer','exists:universities,id'],
            'college_id'    => ['nullable','integer','exists:colleges,id'],
            'major_id'      => ['nullable','integer','exists:majors,id'],
        ]);

        DB::table('users')->where('id',$u->id)->update([
            'university_id' => $data['university_id'] ?? null,
            'college_id'    => $data['college_id'] ?? null,
            'major_id'      => $data['major_id'] ?? null,
            'updated_at'    => now(),
        ]);

        return $this->show();
    }
}
