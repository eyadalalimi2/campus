<?php
namespace App\Http\Controllers\Medical\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medical\SystemSubject;
use App\Models\Medical\System;
use App\Models\Medical\Subject;
use Illuminate\Http\Request;

class SystemSubjectController extends Controller
{
    public function index(Request $r)
    {
        $q = SystemSubject::with(['system','subject'])->orderBy('id','desc');
        if($r->filled('system_id')) $q->where('system_id',$r->get('system_id'));
        if($r->filled('subject_id')) $q->where('subject_id',$r->get('subject_id'));
        $items = $q->paginate(20)->appends($r->query());

        return view('medical.admin.system_subjects.index', [
            'items'=>$items,
            'systems'=>System::orderBy('display_order')->get(),
            'subjects'=>Subject::orderBy('code')->get(),
        ]);
    }

    public function create()
    {
        return view('medical.admin.system_subjects.create', [
            'systems'=>System::orderBy('display_order')->get(),
            'subjects'=>Subject::orderBy('code')->get(),
        ]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'system_id'=>'required|exists:med_systems,id',
            'subject_id'=>'required|exists:med_subjects,id',
            'semester_hint'=>'nullable|integer|min:0|max:20',
            'level'=>'nullable|integer|min:0|max:20',
        ]);
        SystemSubject::create($data);
        return redirect()->route('medical.system-subjects.index')->with('ok','تم ربط الجهاز بالمادة');
    }

    public function edit(SystemSubject $system_subject)
    {
        return view('medical.admin.system_subjects.edit', [
            'item'=>$system_subject,
            'systems'=>System::orderBy('display_order')->get(),
            'subjects'=>Subject::orderBy('code')->get(),
        ]);
    }

    public function update(Request $r, SystemSubject $system_subject)
    {
        $data = $r->validate([
            'system_id'=>'required|exists:med_systems,id',
            'subject_id'=>'required|exists:med_subjects,id',
            'semester_hint'=>'nullable|integer|min:0|max:20',
            'level'=>'nullable|integer|min:0|max:20',
        ]);
        $system_subject->update($data);
        return redirect()->route('medical.system-subjects.index')->with('ok','تم التحديث');
    }

    public function destroy(SystemSubject $system_subject)
    {
        $system_subject->delete();
        return back()->with('ok','تم الحذف');
    }
}
