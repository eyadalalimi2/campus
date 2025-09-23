<?php
namespace App\Http\Controllers\Medical\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medical\DoctorSubjectSystem;
use App\Models\Medical\DoctorSubject;
use App\Models\Medical\System;
use Illuminate\Http\Request;

class DoctorSubjectSystemController extends Controller {
    public function index(){
        $items = DoctorSubjectSystem::with(['doctorSubject.doctor','doctorSubject.subject','system'])
            ->orderBy('id','desc')->paginate(20);
        return view('medical.admin.doctor_subject_systems.index', compact('items'));
    }
    public function create(){
        return view('medical.admin.doctor_subject_systems.create', [
            'doctorSubjects'=>DoctorSubject::with(['doctor','subject'])->get(),
            'systems'=>System::orderBy('display_order')->get()
        ]);
    }
    public function store(Request $r){
        $data = $r->validate([
            'doctor_subject_id'=>'required|exists:med_doctor_subjects,id',
            'system_id'=>'required|exists:med_systems,id',
            'playlist_id'=>'nullable|string|max:100',
            'tag'=>'nullable|string|max:100'
        ]);
        DoctorSubjectSystem::create($data);
        return redirect()->route('medical.doctor-subject-systems.index')->with('ok','تم الربط');
    }
    public function edit(DoctorSubjectSystem $doctor_subject_system){
        return view('medical.admin.doctor_subject_systems.edit', [
            'item'=>$doctor_subject_system,
            'doctorSubjects'=>DoctorSubject::with(['doctor','subject'])->get(),
            'systems'=>System::orderBy('display_order')->get()
        ]);
    }
    public function update(Request $r, DoctorSubjectSystem $doctor_subject_system){
        $data = $r->validate([
            'doctor_subject_id'=>'required|exists:med_doctor_subjects,id',
            'system_id'=>'required|exists:med_systems,id',
            'playlist_id'=>'nullable|string|max:100',
            'tag'=>'nullable|string|max:100'
        ]);
        $doctor_subject_system->update($data);
        return redirect()->route('medical.doctor-subject-systems.index')->with('ok','تم التحديث');
    }
    public function destroy(DoctorSubjectSystem $doctor_subject_system){
        $doctor_subject_system->delete();
        return back()->with('ok','تم الحذف');
    }
}
