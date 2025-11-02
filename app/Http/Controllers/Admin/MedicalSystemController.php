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
        $termsByYear = \App\Models\MedicalYear::with(['terms' => function($q){ $q->from('MedicalTerms'); }])->get()->mapWithKeys(function($year) {
            return [$year->id => $year->terms->map(fn($t) => ['id' => $t->id, 'number' => $t->term_number])->values()];
        });
        return view('admin.medical_systems.create', compact('years','devices','termsByYear'));
    }

    public function store(MedicalSystemRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $path = $file->store('medical_systems', 'public');
            $data['image'] = $path;
        } elseif (empty($data['image']) && !empty($data['med_device_id'])) {
            $device = \App\Models\MedDevice::find($data['med_device_id']);
            if ($device) {
                $data['image'] = $device->image_path;
            }
        }
        MedicalSystem::create($data);
        return redirect()->route('admin.medical_systems.index')->with('success','تم الإنشاء');
    }

    public function edit(MedicalSystem $medical_system)
    {
        $years = MedicalYear::with('major')->get()->mapWithKeys(fn($y)=>[$y->id => $y->major->name.' - سنة '.$y->year_number]);
        $devices = \App\Models\MedDevice::orderBy('name')->pluck('name','id');
        $termsByYear = \App\Models\MedicalYear::with('terms')->get()->mapWithKeys(function($year) {
            return [$year->id => $year->terms->map(fn($t) => ['id' => $t->id, 'number' => $t->term_number])->values()];
        });
        return view('admin.medical_systems.edit', ['system' => $medical_system, 'years'=>$years, 'devices'=>$devices, 'termsByYear'=>$termsByYear]);
    }

    public function update(MedicalSystemRequest $request, MedicalSystem $medical_system)
    {
        $data = $request->validated();
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $path = $file->store('medical_systems', 'public');
            $data['image'] = $path;
        } elseif (empty($data['image']) && !empty($data['med_device_id'])) {
            $device = \App\Models\MedDevice::find($data['med_device_id']);
            if ($device) {
                $data['image'] = $device->image_path;
            }
        }
        $medical_system->update($data);
        return redirect()->route('admin.medical_systems.index')->with('success','تم التحديث');
    }

    public function destroy(MedicalSystem $medical_system)
    {
        $medical_system->delete();
        return back()->with('success','تم الحذف');
    }
}