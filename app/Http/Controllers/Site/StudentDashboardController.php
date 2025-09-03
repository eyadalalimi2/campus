<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Content;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // مواد مرتبطة بجامعة/كلية/تخصص الطالب + مواد عامة (global)
        $materials = Material::where(function($q) use ($user) {
                $q->where('scope','global');
            })->orWhere(function($q) use ($user){
                $q->where('scope','university')
                  ->where(function($w) use ($user){
                      $w->where('university_id', $user->university_id)
                        ->when($user->college_id, fn($x)=>$x->where('college_id', $user->college_id))
                        ->when($user->major_id,   fn($x)=>$x->where('major_id',   $user->major_id));
                  });
            })
            ->where('is_active',1)
            ->orderBy('level')
            ->orderBy('name')
            ->limit(12)
            ->get();

        // أحدث محتوى مرتبط بمواد الطالب (إن وُجدت مادة) أو بالنطاق العام
        $materialIds = $materials->pluck('id')->all();
        $latestContents = Content::when($materialIds, fn($q)=>$q->whereIn('material_id',$materialIds))
            ->orWhere(function($q) use ($user){
                $q->where('scope','university')->where('university_id',$user->university_id);
            })
            ->latest()
            ->limit(8)
            ->get();

        // أجهزة مرتبطة بمواد الطالب
        $devices = Device::whereIn('material_id', $materialIds ?: [0])
            ->where('is_active',1)->latest()->limit(8)->get();

        // إحصائيات بسيطة
        $stats = [
            'materials' => $materials->count(),
            'contents'  => $latestContents->count(),
            'devices'   => $devices->count(),
        ];

        return view('student.dashboard', compact('user','materials','latestContents','devices','stats'));
    }
}
