<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeviceRequest;
use App\Models\Device;
use App\Models\Material;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $r)
    {
        $q = Device::with('material')->orderBy('name');

        if ($r->filled('material_id')) $q->where('material_id',$r->material_id);
        if ($s = $r->get('q')) $q->where('name','like',"%$s%");

        $devices = $q->paginate(15)->withQueryString();
        $materials = Material::orderBy('name')->get();

        return view('admin.devices.index', compact('devices','materials'));
    }

    public function create()
    {
        $materials = Material::orderBy('name')->get();
        return view('admin.devices.create', compact('materials'));
    }

    public function store(DeviceRequest $req)
    {
        $data = $req->validated();
        $data['is_active'] = (bool)$req->boolean('is_active');
        Device::create($data);
        return redirect()->route('admin.devices.index')->with('success','تم إضافة الجهاز/المهمة.');
    }

    public function edit(Device $device)
    {
        $materials = Material::orderBy('name')->get();
        return view('admin.devices.edit', compact('device','materials'));
    }

    public function update(DeviceRequest $req, Device $device)
    {
        $data = $req->validated();
        $data['is_active'] = (bool)$req->boolean('is_active');
        $device->update($data);
        return redirect()->route('admin.devices.index')->with('success','تم تحديث الجهاز/المهمة.');
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return back()->with('success','تم حذف الجهاز/المهمة.');
    }
}
