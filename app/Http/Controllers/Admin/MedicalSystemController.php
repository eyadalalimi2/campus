<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicalSystemRequest;
use App\Models\MedicalSystem;
use App\Models\MedicalYear;

class MedicalSystemController extends Controller
{
    public function index()
    {
        $systems = MedicalSystem::with(['year.major','device'])->orderBy('year_id')->orderBy('sort_order')->paginate(20);
        return view('admin.medical_systems.index', compact('systems'));
    }

    public function create()
    {
        $years = MedicalYear::with('major')->get()->mapWithKeys(fn($y)=>[$y->id => $y->major->name.' - سنة '.$y->year_number]);
        $devices = \App\Models\MedDevice::orderBy('name')->pluck('name','id'); // عام
        return view('admin.medical_systems.create', compact('years','devices'));
    }

    public function store(MedicalSystemRequest $request)
    {
        MedicalSystem::create($request->validated());
        return redirect()->route('admin.medical_systems.index')->with('success','تم الإنشاء');
    }

    public function edit(MedicalSystem $medical_system)
    {
        $years = MedicalYear::with('major')->get()->mapWithKeys(fn($y)=>[$y->id => $y->major->name.' - سنة '.$y->year_number]);
        $devices = \App\Models\MedDevice::orderBy('name')->pluck('name','id');
        return view('admin.medical_systems.edit', ['system' => $medical_system, 'years'=>$years, 'devices'=>$devices]);
    }

    public function update(MedicalSystemRequest $request, MedicalSystem $medical_system)
    {
        $medical_system->update($request->validated());
        return redirect()->route('admin.medical_systems.index')->with('success','تم التحديث');
    }

    public function destroy(MedicalSystem $medical_system)
    {
        $medical_system->delete();
        return back()->with('success','تم الحذف');
    }
}