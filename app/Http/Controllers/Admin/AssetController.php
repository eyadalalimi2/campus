<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssetRequest;
use App\Models\Asset;
use App\Models\Material;
use App\Models\Device;
use App\Models\Doctor;
use App\Models\Discipline;
use App\Models\Program;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::with(['material','device','doctor','discipline','program'])
            ->latest()
            ->paginate(15);

        return view('admin.assets.index', compact('assets'));
    }

    public function create()
    {
        $materials   = Material::orderBy('name')->get();
        $devices     = Device::orderBy('name')->get();
        $doctors     = Doctor::orderBy('name')->get();
        $disciplines = Discipline::orderBy('name')->get();
        $programs    = Program::orderBy('name')->get();

        return view('admin.assets.create', compact('materials','devices','doctors','disciplines','programs'));
    }

    public function store(AssetRequest $request)
    {
        $data = $request->validated();
        $asset = new Asset();
        $asset->fill($data);

        // رفع الملف إذا كان مرفقًا
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('assets', 'public');
            $asset->file_path = $path;
        }

        // تعيين بيانات النشر إذا كانت الحالة منشورة
        if ($data['status'] === 'published') {
            $asset->published_by_admin_id = auth('admin')->id();
            $asset->published_at = now();
        }

        $asset->save();

        return redirect()->route('admin.assets.index')->with('success','تم إنشاء الأصل بنجاح.');
    }

    public function edit(Asset $asset)
    {
        $materials   = Material::orderBy('name')->get();
        $devices     = Device::orderBy('name')->get();
        $doctors     = Doctor::orderBy('name')->get();
        $disciplines = Discipline::orderBy('name')->get();
        $programs    = Program::orderBy('name')->get();

        return view('admin.assets.edit', compact('asset','materials','devices','doctors','disciplines','programs'));
    }

    public function update(AssetRequest $request, Asset $asset)
    {
        $data = $request->validated();
        $asset->fill($data);

        if ($request->hasFile('file')) {
            // حذف الملف القديم إن وجد
            if ($asset->file_path) {
                Storage::disk('public')->delete($asset->file_path);
            }
            $path = $request->file('file')->store('assets','public');
            $asset->file_path = $path;
        }

        if ($data['status'] === 'published' && !$asset->published_at) {
            $asset->published_by_admin_id = auth('admin')->id();
            $asset->published_at = now();
        }

        $asset->save();

        return redirect()->route('admin.assets.index')->with('success','تم تحديث الأصل بنجاح.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('admin.assets.index')->with('success','تم حذف الأصل بنجاح.');
    }
}
