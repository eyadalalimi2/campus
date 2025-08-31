<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssetRequest;
use App\Models\Asset;
use App\Models\Material;
use App\Models\Device;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Request $r)
    {
        $q = Asset::with(['material','device','doctor'])->latest();

        if ($r->filled('category')) $q->where('category',$r->category);
        if ($r->filled('material_id')) $q->where('material_id',$r->material_id);
        if ($r->filled('device_id')) $q->where('device_id',$r->device_id);
        if ($r->filled('doctor_id')) $q->where('doctor_id',$r->doctor_id);
        if ($r->filled('is_active')) $q->where('is_active',(int)$r->is_active);
        if ($s = $r->get('q')) $q->where('title','like',"%$s%");

        $assets = $q->paginate(15)->withQueryString();

        $materials = Material::orderBy('name')->get();
        $devices   = Device::orderBy('name')->get();
        $doctors   = Doctor::orderBy('name')->get();

        return view('admin.assets.index', compact('assets','materials','devices','doctors'));
    }

    public function create()
    {
        $materials = Material::with(['devices'])->orderBy('name')->get();
        $devices   = Device::orderBy('name')->get();
        $doctors   = Doctor::orderBy('name')->get();
        return view('admin.assets.create', compact('materials','devices','doctors'));
    }

    public function store(AssetRequest $req)
    {
        $data = $req->validated();
        $data['is_active'] = (bool)$req->boolean('is_active');

        // إدارة الملف/الروابط
        if ($data['category'] === 'file') {
            $data['file_path']   = $req->file('file')->store('assets','public');
            $data['video_url']   = null;
            $data['external_url']= null;
        } elseif ($data['category'] === 'youtube') {
            $data['video_url']   = $req->video_url;
            $data['file_path']   = null;
            $data['external_url']= null;
        } else {
            // reference/question_bank/curriculum/book
            if ($req->hasFile('file')) {
                $data['file_path'] = $req->file('file')->store('assets','public');
                $data['external_url'] = null;
            } else {
                $data['file_path'] = null;
                $data['external_url'] = $req->external_url;
            }
            $data['video_url'] = null;
        }

        Asset::create($data);

        return redirect()->route('admin.assets.index')->with('success','تم إضافة العنصر.');
    }

    public function edit(Asset $asset)
    {
        $materials = Material::with(['devices'])->orderBy('name')->get();
        $devices   = Device::orderBy('name')->get();
        $doctors   = Doctor::orderBy('name')->get();
        return view('admin.assets.edit', compact('asset','materials','devices','doctors'));
    }

    public function update(AssetRequest $req, Asset $asset)
    {
        $data = $req->validated();
        $data['is_active'] = (bool)$req->boolean('is_active');

        if ($data['category'] === 'file') {
            if ($req->hasFile('file')) {
                if ($asset->file_path) Storage::disk('public')->delete($asset->file_path);
                $data['file_path'] = $req->file('file')->store('assets','public');
            }
            $data['video_url'] = null;
            $data['external_url'] = null;
        } elseif ($data['category'] === 'youtube') {
            if ($asset->file_path) { Storage::disk('public')->delete($asset->file_path); }
            $data['file_path'] = null;
            $data['video_url'] = $req->video_url;
            $data['external_url'] = null;
        } else {
            // باقي الفئات
            if ($req->hasFile('file')) {
                if ($asset->file_path) Storage::disk('public')->delete($asset->file_path);
                $data['file_path'] = $req->file('file')->store('assets','public');
                $data['external_url'] = null;
            } else {
                if ($asset->file_path && $req->filled('external_url')) {
                    Storage::disk('public')->delete($asset->file_path);
                    $data['file_path'] = null;
                }
                $data['external_url'] = $req->external_url;
            }
            $data['video_url'] = null;
        }

        $asset->update($data);

        return redirect()->route('admin.assets.index')->with('success','تم تحديث العنصر.');
    }

    public function destroy(Asset $asset)
    {
        if ($asset->file_path) Storage::disk('public')->delete($asset->file_path);
        $asset->delete();
        return back()->with('success','تم حذف العنصر.');
    }
}
