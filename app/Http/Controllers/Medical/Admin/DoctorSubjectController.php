<?php
namespace App\Http\Controllers\Medical\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medical\DoctorSubject;
use App\Models\Medical\Doctor;
use App\Models\Medical\Subject;
use Illuminate\Http\Request;

class DoctorSubjectController extends Controller {
    public function index(){
        $items = DoctorSubject::with(['doctor','subject'])->orderBy('priority')->paginate(20);
        return view('medical.admin.doctor_subjects.index', compact('items'));
    }
    public function create(){
        return view('medical.admin.doctor_subjects.create', [
            'doctors'=>Doctor::orderBy('name')->get(),
            'subjects'=>Subject::orderBy('code')->get()
        ]);
    }
    public function store(Request $r){
        $data = $r->validate([
            'doctor_id'=>'required|exists:med_doctors,id',
            'subject_id'=>'required|exists:med_subjects,id',
            'priority'=>'nullable|integer|min:0|max:9',
            'featured'=>'nullable|boolean'
        ]);
        $data['featured'] = (bool)($data['featured'] ?? false);
        DoctorSubject::create($data);
        return redirect()->route('medical.doctor-subjects.index')->with('ok','تم الربط');
    }
    public function edit(DoctorSubject $doctor_subject){
        return view('medical.admin.doctor_subjects.edit', [
            'item'=>$doctor_subject,
            'doctors'=>Doctor::orderBy('name')->get(),
            'subjects'=>Subject::orderBy('code')->get()
        ]);
    }
    public function update(Request $r, DoctorSubject $doctor_subject){
        $data = $r->validate([
            'doctor_id'=>'required|exists:med_doctors,id',
            'subject_id'=>'required|exists:med_subjects,id',
            'priority'=>'nullable|integer|min:0|max:9',
            'featured'=>'nullable|boolean'
        ]);
        $data['featured'] = (bool)($data['featured'] ?? false);
        $doctor_subject->update($data);
        return redirect()->route('medical.doctor-subjects.index')->with('ok','تم التحديث');
    }
    public function destroy(DoctorSubject $doctor_subject){
        $doctor_subject->delete();
        return back()->with('ok','تم الحذف');
    }
}
