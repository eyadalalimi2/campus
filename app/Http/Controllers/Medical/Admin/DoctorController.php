<?php
namespace App\Http\Controllers\Medical\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medical\DoctorRequest;
use App\Models\Medical\Doctor;

class DoctorController extends Controller {
    public function index(){ $items = Doctor::orderByDesc('verified')->orderBy('name')->paginate(20); return view('medical.admin.doctors.index', compact('items')); }
    public function create(){ return view('medical.admin.doctors.create'); }
    public function store(DoctorRequest $req){ Doctor::create($req->validated()); return redirect()->route('medical.doctors.index')->with('ok','تم الإنشاء'); }
    public function edit(Doctor $doctor){ return view('medical.admin.doctors.edit', compact('doctor')); }
    public function update(DoctorRequest $req, Doctor $doctor){ $doctor->update($req->validated()); return redirect()->route('medical.doctors.index')->with('ok','تم التحديث'); }
    public function destroy(Doctor $doctor){ $doctor->delete(); return back()->with('ok','تم الحذف'); }
}
