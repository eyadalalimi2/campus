<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedDeviceRequest;
use App\Models\MedDevice;
use App\Models\MedSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedDeviceController extends Controller
{
    public function index()
    {
        $devices = MedDevice::orderBy('order_index')->paginate(20);
        return view('admin.med_devices.index', compact('devices'));
    }

    public function create()
    {
        $subjects = MedSubject::orderBy('name')->get();
        return view('admin.med_devices.create', compact('subjects'));
    }

    public function store(MedDeviceRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('med/images','public');
        }
        $device = MedDevice::create($data);
        $device->subjects()->sync($request->input('subject_ids',[]));

        return redirect()->route('admin.med_devices.index')->with('success','تم إنشاء الجهاز بنجاح');
    }

    public function edit(MedDevice $device)
    {
        $subjects = MedSubject::orderBy('name')->get();
        $selected = $device->subjects()->pluck('id')->toArray();
        return view('admin.med_devices.edit', compact('device','subjects','selected'));
    }

    public function update(MedDeviceRequest $request, MedDevice $device)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            if ($device->image_path) Storage::disk('public')->delete($device->image_path);
            $data['image_path'] = $request->file('image')->store('med/images','public');
        }
        $device->update($data);
        $device->subjects()->sync($request->input('subject_ids',[]));

        return redirect()->route('admin.med_devices.index')->with('success','تم تحديث الجهاز بنجاح');
    }

    public function destroy(MedDevice $device)
    {
        if ($device->image_path) Storage::disk('public')->delete($device->image_path);
        $device->subjects()->detach();
        $device->delete();
        return back()->with('success','تم الحذف');
    }
}
