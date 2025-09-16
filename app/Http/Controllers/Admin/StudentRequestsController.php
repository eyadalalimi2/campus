<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StudentRequestsController extends Controller
{
    public function index(Request $r)
    {
        $status = $r->query('status');
        $requests = DB::table('student_requests')
            ->when($status, fn($q)=>$q->where('status',$status))
            ->orderBy('created_at','desc')->paginate(20);

        return view('admin.requests.index', compact('requests','status'));
    }

    public function show($id)
    {
        $row = DB::table('student_requests')->find($id);
        abort_unless($row, 404);
        return view('admin.requests.show', compact('row'));
    }

    public function assign(Request $r, $id)
    {
        $data = $r->validate([
            'assigned_to_admin_id'=>['required','integer','exists:admins,id']
        ]);
        DB::table('student_requests')->where('id',$id)->update([
            'assigned_to_admin_id'=>$data['assigned_to_admin_id'],
            'updated_at'=>now()
        ]);
        return back()->with('ok','تم التعيين.');
    }

    public function changeStatus(Request $r, $id)
    {
        $data = $r->validate([
            'status'=>['required','in:open,in_progress,resolved,closed'],
            'admin_notes'=>['nullable','string','max:2000']
        ]);
        DB::table('student_requests')->where('id',$id)->update([
            'status'=>$data['status'],
            'admin_notes'=>$data['admin_notes'] ?? null,
            'updated_at'=>now()
        ]);
        return back()->with('ok','تم تحديث الحالة.');
    }

    public function close($id)
    {
        DB::table('student_requests')->where('id',$id)->update([
            'status'=>'closed','updated_at'=>now()
        ]);
        return back()->with('ok','تم الإغلاق.');
    }
}
