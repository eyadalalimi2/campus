<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContentRequest;
use App\Models\Content;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use App\Models\Material;
use App\Models\Doctor;
use App\Models\Device;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index()
    {
        $contents = Content::with(['university','college','major','material','doctor'])
            ->latest()
            ->paginate(15);

        return view('admin.contents.index', compact('contents'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $materials    = Material::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();
        $devices      = Device::orderBy('name')->get();

        return view('admin.contents.create', compact('universities','colleges','majors','materials','doctors','devices'));
    }

    public function store(ContentRequest $request)
    {
        $data = $request->validated();
        $content = new Content();
        $content->fill($data);

        // رفع الملف
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('contents','public');
            $content->file_path = $path;
        }

        if ($data['status'] === 'published') {
            $content->published_by_admin_id = auth('admin')->id();
            $content->published_at = now();
        }

        $content->save();

        if (!empty($data['device_ids'])) {
            $content->devices()->sync($data['device_ids']);
        }

        return redirect()->route('admin.contents.index')->with('success','تم إنشاء المحتوى بنجاح.');
    }

    public function edit(Content $content)
    {
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        $materials    = Material::orderBy('name')->get();
        $doctors      = Doctor::orderBy('name')->get();
        $devices      = Device::orderBy('name')->get();

        return view('admin.contents.edit', compact('content','universities','colleges','majors','materials','doctors','devices'));
    }

    public function update(ContentRequest $request, Content $content)
    {
        $data = $request->validated();
        $content->fill($data);

        if ($request->hasFile('file')) {
            if ($content->file_path) {
                Storage::disk('public')->delete($content->file_path);
            }
            $path = $request->file('file')->store('contents','public');
            $content->file_path = $path;
        }

        if ($data['status'] === 'published' && !$content->published_at) {
            $content->published_by_admin_id = auth('admin')->id();
            $content->published_at = now();
        }

        $content->save();

        $content->devices()->sync($data['device_ids'] ?? []);

        return redirect()->route('admin.contents.index')->with('success','تم تحديث المحتوى بنجاح.');
    }

    public function destroy(Content $content)
    {
        $content->delete();
        return redirect()->route('admin.contents.index')->with('success','تم حذف المحتوى بنجاح.');
    }
}
